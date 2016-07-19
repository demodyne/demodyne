<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */
namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DGIModule\Entity\City;

class LocationController extends AbstractActionController
{

    public function getCitiesAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        if ($request->isPost()) {
            $postalcode = $this->params()->fromPost('postalcode');
            $countryId = $this->params()->fromPost('country');
            
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            
            $country = $entityManager->getRepository('DGIModule\Entity\Country')->findOneBy([
                'countryId' => $countryId
            ]);
            
            $cities = $entityManager->getRepository('DGIModule\Entity\City')->findBy([
                'cityPostalcode' => $postalcode,
                'country' => $country
            ]);
            
            $cityList = array();
            
            foreach ($cities as $index => $city) {
                $cityList[$index] = [
                    "name" => $city->getCityName(),
                    "id" => $city->getCityId()
                ];
            }
            
            return new \Zend\View\Model\JsonModel($cityList);
        }
    }

    public function citiesAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $postalcode = $this->params('id');
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $cities = $entityManager->getRepository('DGIModule\Entity\City')->findBy([
            'cityPostalcode' => $postalcode
        ]);
        
        $cityList = array();
        
        foreach ($cities as $city) {
            $cityList["name"][] = $city->getCityName();
            $cityList["id"][] = $city->getCityId();
        }
        
        return $response->setContent(\Zend\Json\Json::encode($cityList));
    }

    public function getDepartmentsAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        if ($request->isPost()) {
            $countryId = $this->params()->fromPost('country');
            
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $country = $entityManager->getRepository('DGIModule\Entity\Country')->findOneBy([
                'countryId' => $countryId
            ]);
            $departments = $entityManager->getRepository('DGIModule\Entity\Department')->findBy([
                'country' => $country
            ]);
            $depList = array();
            foreach ($departments as $department) {
                $depList[$department->getRegion()->getRegionName()]['dep'][$department->getDepId()] = $department->getDepName();
            }
            ksort($depList);
            $depList['Other']['dep'][0] = 'Other';
            
            return $response->setContent(\Zend\Json\Json::encode($depList));
        }
    }

    public function getRegionsAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        if ($request->isPost()) {
            $countryId = $this->params()->fromPost('country');
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            
            $regions = $entityManager->getRepository('DGIModule\Entity\Region')->getAllRegionsCountryId($countryId);
            
            $regionList = array();
            
            foreach ($regions as $index => $region) {
                $regionList[$index] = [
                    "name" => $region->getRegionName(),
                    "id" => $region->getRegionId()
                ];
            }
            
            return new \Zend\View\Model\JsonModel($regionList);
        }
    }
}