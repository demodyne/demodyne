<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use DGIModule\Entity\Category;

class CategoryController extends AbstractActionController
{
    public function indexAction()
    {
        $user = $this->identity();
       
        $countryId = $this->params()->fromRoute('country', 73);
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $countries = $entityManager->getRepository('DGIModule\Entity\Country')->getAllCountries();
        $country = $entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $countryId]);
        
        $categories = $entityManager->getRepository('DGIModule\Entity\Category')->getMainCategoriesCountry($country);
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $cityView = $this->params()->fromPost('city');
            $regionView= $this->params()->fromPost('region');
            $countryView = $this->params()->fromPost('country');
            
            foreach ($categories as $mainCategory) {
                $mainCategory->setCatCity(array_key_exists($mainCategory->getCatId(), $cityView)?1:0);
                $mainCategory->setCatRegion(array_key_exists($mainCategory->getCatId(), $regionView)?1:0);
                $mainCategory->setCatCountry(array_key_exists($mainCategory->getCatId(), $countryView)?1:0);
                $entityManager->merge($mainCategory);
                foreach ($mainCategory->getSubCategories() as $category) {
                    $category->setCatCity(array_key_exists($category->getCatId(), $cityView)?1:0);
                    $category->setCatRegion(array_key_exists($category->getCatId(), $regionView)?1:0);
                    $category->setCatCountry(array_key_exists($category->getCatId(), $countryView)?1:0);
                    $entityManager->merge($category);
                }
            }
            $entityManager->flush();
            $categories = $entityManager->getRepository('DGIModule\Entity\Category')->getMainCategoriesCountry($country);
        }

		return new ViewModel([
		    'categories' => $categories,
		    'countries' => $countries,
		    'selectedCountry' => $country
		]);
	}	
	
    public function addAction()
    {
        $user = $this->identity();
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $id = $this->params()->fromRoute('id', 0);
        $countryId = $this->params()->fromRoute('country', 73);
        
        $parentCategory = ($id==0)?null:$entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(array('catId' => $id));
        $country = $entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $countryId]);
        
        $category = new Category();
        $builder    = new AnnotationBuilder();
        
        $form       = $builder->createForm($category);
        $form->get('submit')->setValue('Add');
        $form->setHydrator(new DoctrineHydrator($entityManager,'DGIModule\Entity\Category'));
        
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
                $category->setCatCat($parentCategory)
                               ->setCountry($country);
                if (!$parentCategory) {
                    $category->setCatImage($filename.'.'.pathinfo($post["catImage"]["name"], PATHINFO_EXTENSION));
                }
                else {
                    $category->setCatImage($parentCategory->getCatImage());
                }
                
                $entityManager->persist($category);
                $entityManager->flush();
                
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
                    $entityManager->persist($otherCategory);
                    $entityManager->flush();
                }
                
                return $this->redirect()->toRoute('home/category', ['country' => $countryId]);
            }
            print_r($form->getMessages());	
        }
         
        return array(
            'form'=>$form,
            'parentCategory'=>$parentCategory,
            'countryId' => $countryId
        );
    }
    
    public function editAction()
    {
        $user = $this->identity();
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    
        $id = $this->params()->fromRoute('id', 0);
    
        $category = $entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(array('catId' => $id));
        $filename=$category->getCatImage();
    
        $builder    = new AnnotationBuilder();
    
        $form       = $builder->createForm($category);
        $form->setHydrator(new DoctrineHydrator($entityManager,'DGIModule\Entity\Category'));
        
        
        $form->get('catName')->setValue($category->getCatName());
        $form->get('catDescription')->setValue($category->getCatDescription());
        $form->get('catCity')->setValue($category->getCatCity())->setAttribute('checked', $category->getCatCity()?'checked':false);
        $form->get('catRegion')->setValue($category->getCatRegion())->setAttribute('checked', $category->getCatRegion()?'checked':false);
        $form->get('catCountry')->setValue($category->getCatCountry())->setAttribute('checked', $category->getCatCountry()?'checked':false);
        
        $form->get('submit')->setValue('Edit');
        
        
        $filter = $form->getInputFilter();
        $filter->add(array(
            'name' => 'catImage',
            'required' => false,
            'filters' => [
                [
                    'name'=>'Zend\Filter\File\RenameUpload', 
                    'options'=>[
                        'target' => 'public/files/'.$category->getCatImage(), 
                        'overwrite '=>'true'
                    ]
                ]
            ]
        ));
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
                $category->setCatImage($filename);
                
                $entityManager->merge($category);
                $entityManager->flush();
                
                $files   = $request->getFiles();
                return $this->redirect()->toRoute('home/category');
            }
        }
         
        return array(
            'form'=>$form,
            'category'=>$category
        );
    }
    
    public function deleteAction()
    {
        
        $user = $this->identity();
        
        
        $id = $this->params('id');
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $category = $entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(array('catId' => $id));
        
        if (!$category) {
            return $this->redirect()->toRoute('home/category');
        }
    
        $request = $this->getRequest();
    
        $viewmodel = new ViewModel();
        //disable layout if request by Ajax
        $is_xmlhttprequest = $this->getRequest()->isXmlHttpRequest();
        $viewmodel->setTerminal($is_xmlhttprequest);
    
    
        if ($request->isPost()) {
            if ($request->getPost()->get('del') == 'Yes') {
               $entityManager->remove($category);
               $entityManager->flush();
            }
    
            return $this->redirect()->toRoute('home/category');
        }
    
        $viewmodel->setVariables(array(
            'id' => $id,
            'category' => $category,
            'is_xmlhttprequest' => $is_xmlhttprequest
        ));
    
        return $viewmodel;
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
            
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    
            $categories = $entityManager->getRepository('DGIModule\Entity\Category')->getMainCategories($user, $level);
    
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
    
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    
            $categories = $entityManager->getRepository('DGIModule\Entity\Category')->getSubCategories($mainCategoryId);
    
            $catList = array();
    
            foreach ($categories as $category) {
                $catList["name"][] = $category->getCatName();
                $catList["id"][] = $category->getCatId();
    
            }
    
            return $response->setContent(\Zend\Json\Json::encode($catList));
        }
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
    
            
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            
            $categories = $entityManager->getRepository('DGIModule\Entity\Category')->getSubCategories($mainCategoryId, $level);
    
            $catList = array();
            
            foreach ($categories as $category) {
                $catList["name"][] = $category->getCatName();
                $catList["id"][] = $category->getCatId();
                
            }
            
            return $response->setContent(\Zend\Json\Json::encode($catList));
        }
        else {
            $mainCategoryId = $this->params('id');
        
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
            $categories = $entityManager->getRepository('DGIModule\Entity\Category')->getSubCategories($mainCategoryId);
        
            $catList = array();
        
            foreach ($categories as $category) {
                $catList["name"][] = $category->getCatName();
                $catList["id"][] = $category->getCatId();
        
            }
        
            return $response->setContent(\Zend\Json\Json::encode($catList));
        }
    }
}