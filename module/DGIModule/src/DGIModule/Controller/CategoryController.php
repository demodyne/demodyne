<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use DGIModule\Entity\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use DGIModule\Entity\Category;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class CategoryController extends AbstractActionController
{

    protected $entityManager;
    protected $translator;
    protected $config;

    public function __construct(
        array $config,
        EntityManager $entityManager,
        Translator $translator
    )
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }
    
    public function indexAction()
    {
        $countryId = $this->params()->fromRoute('country', 73);

        $countries = $this->entityManager->getRepository('DGIModule\Entity\Country')->getAllCountries();
        $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $countryId]);
        
        $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getMainCategoriesCountry($country);

        $request = $this->getRequest();
        if ($request->isPost()){
            $cityView = $this->params()->fromPost('city');
            $regionView= $this->params()->fromPost('region');
            $countryView = $this->params()->fromPost('country');
            
            foreach ($categories as $mainCategory) {
                $mainCategory->setCatCity(array_key_exists($mainCategory->getCatId(), $cityView)?1:0);
                $mainCategory->setCatRegion(array_key_exists($mainCategory->getCatId(), $regionView)?1:0);
                $mainCategory->setCatCountry(array_key_exists($mainCategory->getCatId(), $countryView)?1:0);
                $this->entityManager->merge($mainCategory);
                foreach ($mainCategory->getSubCategories() as $category) {
                    $category->setCatCity(array_key_exists($category->getCatId(), $cityView)?1:0);
                    $category->setCatRegion(array_key_exists($category->getCatId(), $regionView)?1:0);
                    $category->setCatCountry(array_key_exists($category->getCatId(), $countryView)?1:0);
                    $this->entityManager->merge($category);
                }
            }
            $this->entityManager->flush();
            $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getMainCategoriesCountry($country);
        }

		return new ViewModel([
		    'categories' => $categories,
		    'countries' => $countries,
		    'selectedCountry' => $country
		]);
	}	
	
    public function addAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $countryId = $this->params()->fromRoute('country', 73);

        $parentCategory = ($id==0)?null:$this->entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(array('catId' => $id));
        $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $countryId]);
        
        $category = new Category();
        $builder    = new AnnotationBuilder();
        
        $form       = $builder->createForm($category);
        $form->get('submit')->setValue('Add');
        $form->setHydrator(new DoctrineHydrator($this->entityManager,'DGIModule\Entity\Category'));
        
        if (!$parentCategory) {
            $filename="catImage".time();
            $filter = $form->getInputFilter();
            $filter->add([
                'name' => 'catImage',
                'required' => true,
                'filters' => [
                       ['name'=>'Zend\Filter\File\RenameUpload',
                        'options'=>[
                            'target' => 'public/files/'.$filename,
                            'use_upload_extension'=>'true',
                            'overwrite '=>'true'
                        ]
                    ]
                ]
            ]);
        
            $form->setInputFilter($filter);
        }
        else {
            $form->remove('catImage')
                 ->getInputFilter()
                    ->remove('catImage');
        }
           
        $form->bind($category);
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
                );

            $form->setData($post);

            if ($form->isValid()){
                // prepare data
                $category->setCatCat($parentCategory)
                               ->setCountry($country);
                if (!$parentCategory) {
                    $category->setCatImage($filename.'.'.pathinfo($post["catImage"]["name"], PATHINFO_EXTENSION));
                }
                else {
                    $category->setCatImage($parentCategory->getCatImage());
                }
                
                $this->entityManager->persist($category);
                $this->entityManager->flush();
                
                $files   = $request->getFiles();
                
                // create Other subcategory for main category
                if (!$parentCategory) {
                    
                    $otherCategoryName = $country->getCountryOtherCategory()!=""?$country->getCountryOtherCategory():_('Other');
                    
                    $otherCategory = new Category();    
                    $otherCategory->setCatCat($category)
                                            ->setCatName($otherCategoryName)
                                            ->setCatDescription($otherCategoryName)
                                            ->setCountry($country)
                                            ->setCatImage($category->getCatImage());
                    $this->entityManager->persist($otherCategory);
                    $this->entityManager->flush();
                }
                
                return $this->redirect()->toRoute('home/category', ['country' => $countryId]);
            }
        }
         
        return array(
            'form'=>$form,
            'parentCategory'=>$parentCategory,
            'countryId' => $countryId
        );
    }
    
    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        /** @var \DGIModule\Entity\Category $category */
        $category = $this->entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(array('catId' => $id));
        $filename=$category->getCatImage();
    
        $builder    = new AnnotationBuilder();
    
        $form       = $builder->createForm($category);
        $form->setHydrator(new DoctrineHydrator($this->entityManager,'DGIModule\Entity\Category'));
        
        $form->get('catName')->setValue($category->getCatName());
        $form->get('catDescription')->setValue($category->getCatDescription());
        $form->get('catCity')->setValue($category->getCatCity())->setAttribute('checked', $category->getCatCity()?'checked':false);
        $form->get('catRegion')->setValue($category->getCatRegion())->setAttribute('checked', $category->getCatRegion()?'checked':false);
        $form->get('catCountry')->setValue($category->getCatCountry())->setAttribute('checked', $category->getCatCountry()?'checked':false);
        
        $form->get('submit')->setValue('Edit');

        $filename="catImage".time();
        $filter = $form->getInputFilter();
        $filter->add([
            'name' => 'catImage',
            'required' => false,
            'filters' => [
                [
                    'name'=>'Zend\Filter\File\RenameUpload', 
                    'options'=>[
                        'target' => 'public/files/'.$filename,
                        'use_upload_extension'=>'true',
                        'overwrite '=>'true'
                    ]
                ]
            ]
        ]);
        $form->setInputFilter($filter);

        $request = $this->getRequest();
        if ($request->isPost()){
            $form->bind($category);
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()){
                // prepare data
                $filename = $filename.'.'.pathinfo($post["catImage"]["name"], PATHINFO_EXTENSION);
                $category->setCatImage($filename);

                if (!$category->getCatCat()) {
                    foreach ($category->getSubCategories() as $subCategory) {
                        $subCategory->setCatImage($filename);
                        $this->entityManager->merge($subCategory);
                    }
                }

                $this->entityManager->merge($category);
                $this->entityManager->flush();
                
                $files   = $request->getFiles();

                return $this->redirect()->toRoute('home/category', ['country'=>$category->getCountry()->getCountryId()]);
            }
        }
         
        return array(
            'form'=>$form,
            'category'=>$category
        );
    }
    
    public function deleteAction()
    {
        $id = $this->params('id');
        
        $category = $this->entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(array('catId' => $id));
        
        if (!$category) {
            return $this->redirect()->toRoute('home/category');
        }
    
        $request = $this->getRequest();
    
        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        $is_xmlhttprequest = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($is_xmlhttprequest);

        if ($request->isPost()) {
            if ($request->getPost()->get('del') == 'Yes') {
               $this->entityManager->remove($category);
               $this->entityManager->flush();
            }
    
            return $this->redirect()->toRoute('home/category');
        }
    
        $viewModel->setVariables(array(
            'id' => $id,
            'category' => $category,
            'is_xmlhttprequest' => $is_xmlhttprequest
        ));
    
        return $viewModel;
    }
    
    public function getCategoriesAction() {
        
        $user = $this->identity();
        
        $request = $this->getRequest();
        $response = $this->getResponse();
    
        $level = $this->params()->fromRoute('level');
    
        if (!$level) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
    
        if ($request->isPost()) {

            $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getMainCategories($user, $level);
    
            $catList = array();
    
            foreach ($categories as $category) {
                $catList["name"][] = $category->getCatName();
                $catList["id"][] = $category->getCatId();
                $catList["image"][] = '/files/'. $category->getCatImage();
            }

            return $response->setContent(\Zend\Json\Json::encode($catList));
        }
        else {
            $mainCategoryId = $this->params('id');
            $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getSubCategories($mainCategoryId);
    
            $catList = array();
    
            foreach ($categories as $category) {
                $catList["name"][] = $category->getCatName();
                $catList["id"][] = $category->getCatId();
            }
            return $response->setContent(\Zend\Json\Json::encode($catList));
        }
    }

    public function getAllCategoriesAction() {

        $country = $this->params()->fromPost('country');

        $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryCode'=>$country]);
        if (!$country) {
            return new JsonModel(array());
        }

        $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getMainCategoriesCountry($country);

        $mainCat = [];

        /** @var \DGIModule\Entity\Category $mainCategory */
        foreach ($categories as $mainCategory) {
            $mainCatItem = [];
            $mainCatItem['name'] = $mainCategory->getCatName();
            $mainCatItem['id'] = $mainCategory->getCatId();
            $mainCatItem["image"] = '/files/' . $mainCategory->getCatImage();
            $subCatItem = [];
            foreach ($mainCategory->getSubCategories() as $category) {
                $subCatItem['name'] = $category->getCatName();
                $subCatItem['id'] = $category->getCatId();
                $mainCatItem['subCategories'][] = $subCatItem;
            }
            $mainCat[] = $mainCatItem;
        }

        return new JsonModel($mainCat);
    }
    
    public function getSubcategoriesAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
    
        $level = $this->params()->fromRoute('level');
        
        if (!$level) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        
        if ($request->isPost()) {
            $mainCategoryId = $this->params()->fromPost('main_category');
            $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getSubCategories($mainCategoryId, $level);
    
            $catList = array();
            
            foreach ($categories as $category) {
                $catList["name"][] = $category->getCatName();
                $catList["id"][] = $category->getCatId();
            }

            return $response->setContent(\Zend\Json\Json::encode($catList));
        }
        else {
            $mainCategoryId = $this->params('id');
            $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getSubCategories($mainCategoryId);
        
            $catList = array();
        
            foreach ($categories as $category) {
                $catList["name"][] = $category->getCatName();
                $catList["id"][] = $category->getCatId();
            }

            return $response->setContent(\Zend\Json\Json::encode($catList));
        }
    }

    public function jsonListAction() {

        $user = $this->identity();

        $level = $this->params()->fromRoute('level');

        if (!$level) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        if (!$user) {
            $user = new User();
            $user->setUsrId(0);
        }
        else {
            $user = clone($user);
        }

        $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => 26241]);

        if ($city) {
            $user->setUsrId(0);
            $user->setCountry($city->getCountry());
            $user->setCity($city);
        }
        $categories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getMainCategories($user, $level);

        $cat = [];

        foreach ($categories as $category) {
            foreach ($category->getSubCategories() as $subCat) {
                $cat[] = [
                    'name' => $category->getCatName().': '.$subCat->getCatName(),
                    'main' => $category->getCatName(),
                    'sub' => $subCat->getCatName(),
                    'id' => $subCat->getCatId(),
                    'img' => $category->getCatImage(),
                ];
            }
        }
        return new JsonModel(array('results' => $cat));
    }
}