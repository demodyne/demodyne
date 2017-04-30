<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */
namespace DGIModule\Controller;

use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class LocationController extends AbstractActionController
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

    public function getCitiesAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postalcode = $this->params()->fromPost('postalcode');
            $countryId = $this->params()->fromPost('country');
            
            $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId'=>$countryId]);
            
            $cities = $this->entityManager->getRepository('DGIModule\Entity\City')->getCities($postalcode,$country);
            
            $cityList = [];
            
            foreach ($cities as $index => $city) {
                $c = $city->getFullCity() && $city->getDistrictCode()==0?$city->getFullCity():$city;
                $cityList[$index] = [
                    "name" => $c->getCityName().' '.$c->getDistrictName(),
                    "id" => $c->getCityId()
                ];
                
            }
            
            return new JsonModel($cityList);
        }
//        else {
//            $postalcode = $this->params()->fromRoute('postalcode');
//            $countryId = $this->params()->fromRoute('country');
//
////            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
//
//            $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId'=>$countryId]);
//
////             $cities = $this->entityManager->getRepository('DGIModule\Entity\City')->findBy(['cityPostalcode'=>$postalcode, 'country' => $country]);
//            $cities = $this->entityManager->getRepository('DGIModule\Entity\City')->getCities($postalcode,$country);
//
//            $cityList = [];
//
//            foreach ($cities as $index => $city) {
//                $c = $city->getFullCity() && $city->getDistrictCode()==0?$city->getFullCity():$city;
//                $cityList[$index] = [
//                    "name" => $c->getCityName().' '.$c->getDistrictName(),
//                    "id" => $c->getCityId()
//                ];
//
//            }
//
//            return new JsonModel($cityList);
//        }
    }
    
    public function citiesAction() {
        $response = $this->getResponse();
        
        $postalcode = $this->params('id');
        $cities = $this->entityManager->getRepository('DGIModule\Entity\City')->findBy(['cityPostalcode'=>$postalcode]);
    
        $cityList = array();

        foreach ($cities as $city) {
            $cityList["name"][] = $city->getCityName();
            $cityList["id"][] = $city->getCityId();

        }

        return $response->setContent(Json::encode($cityList));
    }

    public function getDepartmentsAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        if ($request->isPost()) {
            $countryId = $this->params()->fromPost('country');
            
            $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId'=>$countryId]);
    
            $departments = $this->entityManager->getRepository('DGIModule\Entity\Department')->findBy(['country'=>$country]);
    
            $depList = array();
            foreach ($departments as $department) {
                $depList[$department->getRegion()->getRegionName()]['dep'][$department->getDepId()] = $department->getDepName();
            }
            ksort($depList);
            $depList['Other']['dep'][0] = 'Other';
            
            return $response->setContent(Json::encode($depList));
        }
    }

    public function getRegionsAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $countryId = $this->params()->fromPost('country');
            $regions = $this->entityManager->getRepository('DGIModule\Entity\Region')->getAllRegionsCountryId($countryId);
            
            $regionList = array();

            /** @var \DGIModule\Entity\Region $region */
            foreach ($regions as $index => $region) {
                $regionList[$index] = [
                    "name" => $region->getRegionName(),
                    "id" => $region->getRegionId()
                ];
            }
            
            return new JsonModel($regionList);
        }
    }

    public function getRegionsByCountryCodeAction()
    {
        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $countryCode = $this->params()->fromPost('country');
            $regions = $this->entityManager->getRepository('DGIModule\Entity\Region')->getAllRegionsCountryCode($countryCode);

            $regionList = array();

            /** @var \DGIModule\Entity\Region $region */
            foreach ($regions as $index => $region) {
                $regionList[$index] = [
                    "name" => $region->getRegionName(),
                    "id" => $region->getRegionId()
                ];
            }

            return new JsonModel($regionList);
        }
        return new JsonModel([]);
    }
    
    public function searchCitiesAction() {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $regionId = $this->params()->fromPost('region');
            $countryId = $this->params()->fromPost('country');    
            $search = $this->params()->fromPost('search');

            $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId'=>$countryId]);
            $region = $this->entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionId'=>$regionId]);
            
            $cities = $this->entityManager->getRepository('DGIModule\Entity\City')->searchAllCities($search, $region, $country);
    
            $cityList = array();
    
            foreach ($cities as $index => $city) {
                $c = $city->getFullCity() && $city->getDistrictCode()==0?$city->getFullCity():$city;
                $cityList[$index] = ["name" => $c->getCityName().' '.$c->getDistrictName(), "id" => $c->getCityId()];
            }
    
            $viewModel = new ViewModel();
            $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
            $viewModel->setVariables([
                'cities' => $cities
            ]);
            
            return $viewModel;
        }
    }

    /**
     * @return JsonModel
     */
    public function searchCitiesAllRegionsAction() {

        $regionId = $this->params()->fromQuery('region');
        $countryId = $this->params()->fromQuery('country');
        $search = trim($this->params()->fromQuery('city'));

        /** @var \DGIModule\Entity\Country $country */
        $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryCode'=>$countryId]);

        if (!is_numeric($regionId)) {
            $regionId = 0;
        }

        /** @var \DGIModule\Entity\Region $region */
        $region = $this->entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionId' => $regionId]);

        $cities = $this->entityManager->getRepository('DGIModule\Entity\City')->searchAllCities($search, $region, $country);

        $cityList = [];
        $cityList[] = [
            "label" => !$regionId?sprintf($this->translator->translate('All cities from country %s'),$country->getCountryName()):sprintf($this->translator->translate('All cities from region %s'),$region->getRegionName()),
            "value" => !$regionId?$countryId:-$regionId
        ];

        /** @var \DGIModule\Entity\City $city */
        if (count($cities)) {
            foreach ($cities as $city) {
                $c = $city->getFullCity() && $city->getDistrictCode() == 0 ? $city->getFullCity() : $city;
                $cityList[] = [
                    "label" => $c->getCityName() . ' ' . $c->getDistrictName() . ' (' . $c->getCityPostalcode() . ')',
                    "value" => $c->getCityId()
                ];

            }
        }
        else {
            $cityList[] = [
                "label" => $this->translator->translate('No city found.'),
                "value" => 0
            ];
        }

        return new JsonModel($cityList);
    }
}