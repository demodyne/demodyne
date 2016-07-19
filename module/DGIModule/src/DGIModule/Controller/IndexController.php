<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;



use DGIModule\Form\UserLoginForm;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $redirect = $this->params()->fromQuery('redirect', null); 
        $lang = $this->params('lang', 'en');
        
        $viewModel = new ViewModel();
        
        unset($_SESSION['guest']);
        
        if ($this->identity()) { 
            
            $user=$this->identity();
            if ($user->isPartner()) {
                return $this->redirect()->toRoute('partner/dashboard');
            }
            else {
                $viewModel->setTemplate('dgi-module/index/loggedin.phtml');
                $viewModel->setVariables([
                    'user'=>$user
                ]);
            }
        }
        else {
            $viewModel->setTerminal(true);
            $loginForm = new UserLoginForm();
            $loginForm->get('submit')->setValue('Login');
            $messages = null;
            $viewModel->setVariables([
            	'error' => false,
    			'loginForm'	=> $loginForm,
    			'messages' => $messages,
                'redirect' => $redirect,
                'lang'     => $lang,
    		]);
        }
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $languages = $entityManager->getRepository('DGIModule\Entity\Language')->getLanguages();
        $currentLanguage = $entityManager->getRepository('DGIModule\Entity\Language')->findOneBy(['langId' => $this->getServiceLocator()->get('translator')->getTranslator()->getLocale()]);
        $viewModel->setVariables([
            'languages' => $languages,
	        'currentLanguage' => $currentLanguage
        ]);
        
        return $viewModel;
    }
    
    public function browseAction()
    {
        $user = $this->identity();
        $countryName = $this->params()->fromRoute('country');
        $regionName = $this->params()->fromRoute('region');
        $postalCode = $this->params()->fromRoute('postalcode');
        $cityName = $this->params()->fromRoute('cityname');
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $country = $entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryName' => ucfirst($countryName)]);
        if (!$country) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        
        $guestSession = new Container('guest');
        
        $guestSession->country = $country->getCountryId();
        $guestSession->countryName = $country->getCountryName();
        
        if (!$regionName) {
            $guestSession->level = 'country';
            
            if (!$user || $country!=$user->getCountry()) {
                $view =  $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action'        => 'country-dashboard']);
            }
            else {
                unset($_SESSION['guest']);
                return $this->redirect()->toRoute('country');		
            }
        }
        else {
            
            $guestSession->level = 'region';
            
            $region = $entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionName' => ucfirst($regionName), 'country' => $country]);
            
            if (!$region) {
                return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
            }
            
            if (!$postalCode) {
                if (!$user || $region!=$user->getCiy()->getRegion()) {
                    $view =  $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action' => 'region-dashboard']);
                }
                else {
                    unset($_SESSION['guest']);
                    return $this->redirect()->toRoute('region');
                }
                
            }
            else {
                $city = $entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityPostalcode' => $postalCode, 'cityName' => ucfirst($cityName), 'country' => $country]);
                if (!$city) {
            
                    return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
                }
                else {
            
                    $guestSession->city = $city->getCityId();
                    $guestSession->cityName = $cityName;
                    $guestSession->postalCode = $postalCode;
                    $guestSession->level = 'city';
            
                    if (!$user || $city!=$user->getCity()) {
                        $view =  $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action'        => 'city-dashboard']);
                    }
                    else {
                        unset($_SESSION['guest']);
                        return $this->redirect()->toRoute('city');
                    }
                }
            }
        }
        $viewModel = new ViewModel();
        $viewModel->addChild($view, 'view');
        $viewModel->setVariables([
            'user' => $user
        ]);
        return $viewModel;
    }
    
    public function browseContentAction()
    {
    
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $cities = $entityManager->getRepository('DGIModule\Entity\City')->browseCities();
        $list = [];
        foreach ($cities as $city) {
            $list[$city->getCountry()->getCountryName()][$city->getStateName()][$city->getCityPostalcode()] =$city->getCityName(); 
        }
    
        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'list' => $list
        ]);
        return $viewModel;
    }
    
}

