<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use DGIModule\Entity\City;
use DGIModule\Entity\User;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Query\ResultSetMapping;

use DGIModule\Form\BrowseLevelForm;

use DGIModule\Form\UserLoginForm;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class IndexController extends AbstractActionController
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

        $redirect = $this->params()->fromQuery('redirect', null);

        $viewModel = new ViewModel();
        
        unset($_SESSION['guest']);
        unset($_SESSION['level']);

        if ($user=$this->identity()) {

            if ($user->isPartner()) {
                return $this->redirect()->toRoute('partner/dashboard');
            }
            else {

                $city = $user->getCity();
                $session = new Container('level');
                $session->city = $city?$city->getCityId():null;

                $count = null;
                if ($user->getCity()) {
                    $count = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->getProposalCounts($user, $this->config['demodyne']);
                }

                if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
                    $viewModel->setTemplate('dgi-module/index/loggedin.mobile.phtml');
                }
                else {
                    $viewModel->setTemplate('dgi-module/index/loggedin.phtml');
                }
                $viewModel->setVariables([
                    'user'=>$user,
                    'count' => $count
                ]);
            }
        }
        else {
            $viewModel->setTerminal(true);
            $loginForm = new UserLoginForm($this->entityManager, $this->translator);
           
            $messages = null;
            $viewModel->setVariables([
            	'error' => false,
    			'loginForm'	=> $loginForm,
    			'messages' => $messages,
                'redirect' => $redirect,
    		]);
        }
        $languages = $this->entityManager->getRepository('DGIModule\Entity\Language')->getLanguages();
        $currentLanguage = $this->entityManager->getRepository('DGIModule\Entity\Language')->findOneBy(['langId' => $this->translator->getTranslator()->getLocale()]);
        $viewModel->setVariables([
            'languages' => $languages,
	        'currentLanguage' => $currentLanguage,
            'facebook' => false,
        ]);
        
        return $viewModel;
    }

    /**
     * @return mixed|\Zend\Http\Response|ViewModel
     */
    public function browseAction()
    {
        $user = $this->identity();
        $countryName = $this->params()->fromRoute('country');
        $regionName = $this->params()->fromRoute('region');
        $postalCode = $this->params()->fromRoute('postalcode');
        $cityName = $this->params()->fromRoute('cityname');

        $country = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryName' => ucfirst($countryName)]);
        if (!$country) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        $session = new Container('level');

        /** @var City $city */

        if (!$regionName) {

            $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['country' => $country]);
            if (!$user || $country!=$user->getCountry()) {
                $session->city = $city->getCityId();
                return  $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action' => 'dashboard', 'level' => 'country']);
            }
            else {
                return $this->redirect()->toRoute('country');
            }
        }
        else {
            $region = $this->entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionName' => ucfirst($regionName), 'country' => $country]);
            
            if (!$region) {
                return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
            }
            
            if (!$postalCode) {
                $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['country' => $country, 'region' => $region]);
                if (!$user || $region!=$user->getCity()->getRegion()) {
                    $session->city = $city->getCityId();
                    return $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action' => 'dashboard', 'level' => 'region']);
                }
                else {
                    return $this->redirect()->toRoute('region');
                }
            }
            else {
                $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityPostalcode' => $postalCode, 'cityName' => ucfirst($cityName), 'country' => $country]);
                if (!$city) {
                    return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
                }
                else {
                    if ($city->getFullCity() && $city->getDistrictCode()==0) {
                        $city = $city->getFullCity();
                    }
                    if (!$user || $city!=$user->getCity()) {
                        $session->city = $city->getCityId();
                        return   $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action' => 'dashboard', 'level' => 'city']);
                    }
                    else {
                        return $this->redirect()->toRoute('city');
                    }
                }
            }
        }
    }
    
    public function browseDialogAction()
    {
    
        $user = $this->identity();
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $form = new BrowseLevelForm($this->entityManager);
    
        $viewModel = new ViewModel();
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setVariables([
            'user' => $user,
            'form' => $form
        ]);
        
        return $viewModel;
    }
    
    
    public function browseContentAction()
    {
        $cities = $this->entityManager->getRepository('DGIModule\Entity\City')->browseCities();
        $list = [];
        /** @var City $city */
        foreach ($cities as $city) {
            $list[$city->getCountry()->getCountryName()][$city->getStateName()][$city->getCityPostalcode()] =$city->getCityName(); 
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setVariables([
            'list' => $list
        ]);
        return $viewModel;
    }

    public function resetSearchAction() {
        $user = $this->identity();

        $levelSession = new Container('level');
        $city = $levelSession->city;

        if ($user || $city) {
            if (!$user) {
                $user = new User();
                $user->setUsrId(0);
            }

            if ($city) {

                /** @var City $city */
                $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $city]);

                if ($city && $city!=$user->getCity()) {
                    $user->setUsrId(0);
                    $user->setCountry($city->getCountry());
                    $user->setCity($city);
                }
            }
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            unset($_SESSION['search']);
            $searchSession = new Container('search');
            $keywords = explode(' ', preg_replace('/\s+/', ' ', $this->params()->fromPost('searchKeywords', '')));
            $searchSession->keywords = $keywords;
            /** @var City $city */

            if ($levelSession->level=='country' && $user)  {
                $searchSession->countries = [$user->getCity()->getCountry()->getCountryCode()];
            }
            if ($levelSession->level=='region' && $user)  {
                $searchSession->regions = [$user->getCity()->getRegion()->getRegionId()];
            }
            if ($levelSession->level=='city' && $user)  {
                $searchSession->cities = [$user->getCity()->getCityId()];
            }


            return $this->redirect()->toRoute('search', ['action'=>'search-results']);
        }
        $viewModel = new ViewModel();

        $countries = $this->entityManager->getRepository('DGIModule\Entity\Country')->getAllCountries();

        $viewModel->setVariables([
            'keywords' => '',
            'entityType' => [
                'proposal' => 1,
                'measure' => 1,
                'program' => 1,
                'event' => 1,
                'session' => 1,
                'user' => 1
            ],
            'countries' => $countries,
            'selectedCountries' => ($levelSession->level=='country' && $user)?[$user->getCity()->getCountry()]:[],
            'selectedRegions' => ($levelSession->level=='region' && $user)?[['id'=>$user->getCity()->getRegion()->getRegionId(), 'name' => $user->getCity()->getRegion()->getRegionName()]]:[],
            'selectedCities' => ($levelSession->level=='city' && $user)?[['id'=>$user->getCity()->getCityId(), 'name'=>$user->getCity()->getCityName()]]:[],
            'selectedCategories' => [],
            'user' => $user
        ]);

        $viewModel->setTemplate('dgi-module/index/search-advanced.phtml');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $viewModel->setTerminal($request->isXmlHttpRequest());
        return $viewModel;


    }

    public function searchAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $session = new Container('search');
        if ($request->isPost()){
            $keywords = explode(' ', preg_replace('/\s+/', ' ', $this->params()->fromPost('searchKeywords', '')));
            $session->keywords = $keywords;
            $entityType = $this->params()->fromPost('entityType', ['proposal', 'measure', 'program', 'event', 'session', 'user']);
            $session->entityType = $entityType;
            return $this->redirect()->toRoute('search', ['action'=>'search-results']);
        }
        else {

            /** @var \DGIModule\Entity\User $user */
            $user = $this->identity();

            $session = new Container('level');
            $city = $session->city;
            if ($user || $city) {
                if (!$user) {
                    $user = new User();
                    $user->setUsrId(0);
                }

                if ($city) {
                    /** @var City $city */
                    $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $city]);

                    if ($city && $city!=$user->getCity()) {
                        $user->setUsrId(0);
                        $user->setCountry($city->getCountry());
                        $user->setCity($city);
                    }
                }
            }

            $session = new Container('search');

            $entityType = [
                'proposal' => 1,
                'measure' => 1,
                'program' => 1,
                'event' => 1,
                'session' => 1,
                'user' => 1
            ];
            if (isset($session->entityType)) {
                foreach ($entityType as $type => $value) {
                    if (array_search($type, $session->entityType)===false) {
                        $entityType[$type] = 0;
                    }
                }
            }

            $countries = $this->entityManager->getRepository('DGIModule\Entity\Country')->getAllCountries();

            $selectedCountries = $this->entityManager->getRepository('DGIModule\Entity\Country')->getCountriesByArrayCode(isset($session->countries)?$session->countries:[]);

            $selectedCategories = $this->entityManager->getRepository('DGIModule\Entity\Category')->getCategoriesByArrayId(isset($session->categories)?$session->categories:[]);

            if (!$session->regions) {
                $session->regions = [];
            }

            if (!$session->cities) {
                $session->cities = [];
            }

            $regionList = [];
            foreach ($session->regions as $region) {
                $regionEntity['id'] = $region;
                if (is_numeric($region)) {
                    /** @var \DGIModule\Entity\Region $r */
                    $r = $this->entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionId' => $region]);
                    $regionEntity['name'] = $r->getRegionName();
                }
                else {
                    /** @var \DGIModule\Entity\Country $c */
                    $c = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryCode' => $region]);
                    $regionEntity['name'] = sprintf($this->translator->translate('All regions from %s', 'DGIModule'),$c->getCountryName());
                }
                $regionList[] = $regionEntity;
            }

            $cityList = [];
            foreach ($session->cities as $city) {
                $cityEntity['id'] = $city;
                if (is_numeric($city)) {
                    if ($city<0) {
                        /** @var \DGIModule\Entity\Region $r */
                        $r = $this->entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionId' => -$city]);
                        $cityEntity['name'] = sprintf($this->translator->translate('All cities from region %s', 'DGIModule'), $r->getRegionName());
                    }
                    else {
                        /** @var City $c */
                        $c = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $city]);
                        $cityEntity['name'] = $c->getCityName().' '.$c->getDistrictName().' ('.$c->getCityPostalCode().')';
                    }
                }
                else {
                    /** @var \DGIModule\Entity\Country $c */
                    $c = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryCode' => $city]);
                    $cityEntity['name'] = sprintf($this->translator->translate('All cities from country %s', 'DGIModule'),$c->getCountryName());
                }
                $cityList[] = $cityEntity;
            }

            $viewModel->setVariables([
                'keywords' => isset($session->keywords)?implode(' ', $session->keywords):'',
                'entityType' => $entityType,
                'countries' => $countries,
                'selectedCountries' => $selectedCountries,
                'selectedRegions' => $regionList,
                'selectedCities' => $cityList,
                'selectedCategories' => $selectedCategories,
                'mainCategories' => [],
                'user' => $user
            ]);

            if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
                $viewModel->setTemplate('dgi-module/index/search-advanced.mobile.phtml');
            }
            else {
                $viewModel->setTemplate('dgi-module/index/search-advanced.phtml');
            }
        }

        $viewModel->setTerminal($request->isXmlHttpRequest());
        return $viewModel;
    }

    /**
     * @return ViewModel|Response
     */
    public function searchResultsAction() {
        $user = $this->identity();

        $session = new Container('search');
        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->searchAllPage) {
                $page = 1;
            }
            else {
                $page = $session->searchAllPage;
            }
        }
        $session->searchAllPage = $page;
        $filter = $this->params()->fromRoute('filter', null);
        if (!$filter) {
            if (!$session->searchAllFilter) {
                $filter = 'none';
            }
            else {
                $filter = $session->searchAllFilter;
            }
        }
        $session->searchAllFilter = $filter;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->searchAllResults) {
                $limit = 5;
            }
            else {
                $limit = $session->searchAllResults;
            }
        }
        $session->searchAllResults = $limit;
        $sort = $this->params()->fromRoute('sort', null);
        if (!$sort) {
            if (!$session->searchAllSort) {
                $sort = 'name';
            }
            else {
                $sort = $session->searchAllSort;
            }
        }
        $session->searchAllSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (!$order) {
            if (!$session->searchAllOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->searchAllOrder;
            }
        }
        $session->searchAllOrder = $order;

        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;

        //defaults
        if (!$session->keywords) {
            $session->keywords = [];
        }

        if (!$session->entityType) {
            $session->entityType = ['proposal', 'measure', 'program', 'event', 'session', 'user'];
        }

        if (!$session->countries) {
            $session->countries = [];
        }

        if (!$session->regions) {
            $session->regions = [];
        }

        if (!$session->cities) {
            $session->cities = [];
        }

        if (!$session->categories) {
            $session->categories = [];
        }

        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        if ($request->isPost()){
            $keywords = explode(' ', preg_replace('/\s+/', ' ', trim($this->params()->fromPost('searchKeywords', ''))));
            if (count($keywords) && $keywords[0]!="") {
                $session->keywords = $keywords;
            }
            else {
                $session->keywords = [];
            }

            $session->entityType = $this->params()->fromPost('entityType', ['proposal', 'measure', 'program', 'event', 'session', 'user']);

            $session->countries = $this->params()->fromPost('country', []);

            $session->regions = $this->params()->fromPost('region', []);

            $session->cities = $this->params()->fromPost('city', []);

            $session->categories = $this->params()->fromPost('category', []);

        }

        $criteriaType = $this->params()->fromRoute('type');
        if ($criteriaType) {
            $id = $this->params()->fromRoute('id');
            if ($criteriaType=='entity') {
                $removeEntityPosition=array_search($id, $session->entityType);
                if ($removeEntityPosition !== false) {
                    unset($session->entityType[$removeEntityPosition]);
                }
                else {
                    $session->entityType[] = $id;
                }
            }
            elseif ($criteriaType=='keyword') {
                unset($session->keywords[array_search($id, $session->keywords)]);
            }
            elseif ($criteriaType=='country') {
                unset($session->countries[array_search($id, $session->countries)]);
            }
            elseif ($criteriaType=='region') {
                unset($session->regions[array_search($id, $session->regions)]);
            }
            elseif ($criteriaType=='city') {
                unset($session->cities[array_search($id, $session->cities)]);
            }
            elseif ($criteriaType=='category') {
                unset($session->categories[array_search($id, $session->categories)]);
            }
            return $this->redirect()->toRoute('search', ['action'=>'search-results']);
        }

        // @todo ignore special characters
        // @todo search strings with "text"
        $regSQL = [];
        if (!isset($session->keywords)) {

        }
        foreach ($session->keywords as $keyword) {
            $regSQL[] = '(name LIKE "%'.$keyword.'%" OR description LIKE "%'.$keyword.'%" OR username LIKE "%'.$keyword.'%")';
        }

        $levelSQL = [];$levelUserSQL = [];$countryList = [];
        foreach ($session->countries as $country) {
            $c = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryCode' => $country]);
            $levelSQL[] = 'countryCode="'.$country.'" AND level='.$this->config['demodyne']['level']['country'];
            $levelUserSQL[] = 'countryCode="'.$country.'"';
            $countryList[] = [
                'code' => $country,
                'name' => $c->getCountryName()
            ];
        }

        $regionList = [];
        foreach ($session->regions as $region) {
            $regionEntity['id'] = $region;
            if (is_numeric($region)) {
                $levelSQL[] = 'regionId="' . $region . '" AND level=' . $this->config['demodyne']['level']['region'];
                $levelUserSQL[] = 'regionId=' . $region;
                $r = $this->entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionId' => $region]);
                $regionEntity['name'] = $r->getRegionName();
            }
            else {
                $levelSQL[] = 'countryCode="'.$region.'" AND level='.$this->config['demodyne']['level']['region'];
                $levelUserSQL[] = 'countryCode="'.$region.'"';
                $c = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryCode' => $region]);
                $regionEntity['name'] = sprintf($this->translator->translate('All regions from %s', 'DGIModule'),$c->getCountryName());
            }
            $regionList[] = $regionEntity;
        }

        $cityList = [];
        foreach ($session->cities as $city) {
            $cityEntity['id'] = $city;
            if (is_numeric($city)) {
                if ($city<0) {
                    $levelSQL[] = 'regionId="' . -$city . '" AND level=' . $this->config['demodyne']['level']['city'];
                    $levelUserSQL[] = 'regionId=' . -$city;
                    $r = $this->entityManager->getRepository('DGIModule\Entity\Region')->findOneBy(['regionId' => -$city]);
                    $cityEntity['name'] = sprintf($this->translator->translate('All cities from region %s', 'DGIModule'), $r->getRegionName());
                }
                else {
                    $levelSQL[] = '(cityId="'.$city.'" OR fullCityId="'.$city.'") AND level='.$this->config['demodyne']['level']['city'];
                    $levelUserSQL[] = 'cityId=' . $city;
                    $c = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $city]);
                    $cityEntity['name'] = $c->getCityName().' '.$c->getDistrictName().' ('.$c->getCityPostalCode().')';
                }
            }
            else {
                $levelSQL[] = 'countryCode="'.$city.'" AND level='.$this->config['demodyne']['level']['city'];
                $levelUserSQL[] = 'countryCode="'.$city.'"';
                $c = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryCode' => $city]);
                $cityEntity['name'] = sprintf($this->translator->translate('All cities from country %s', 'DGIModule'),$c->getCountryName());
            }
            $cityList[] = $cityEntity;
        }

        $categorySQL = [];$categoryList = [];
        foreach ($session->categories as $category) {
            $c = $this->entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(['catId' => $category]);
            $categorySQL[] = 'mainCatId='.$category;
            $categoryList[] = [
                'id' => $category,
                'name' => $c->getCatName(),
                'image' => '/files/' . $c->getCatImage()
            ];
        }

        $entityType = [
            'proposal' => 0,
            'measure' => 0,
            'program' => 0,
            'event' => 0,
            'session' => 0,
            'user' => 0
        ];
        $ent = false;
        foreach ($session->entityType as $type) {
            $ent = true;
            $entityType[$type] = 1;
        }
        if (!$ent) {
            $entityType = [
                'proposal' => 1,
                'measure' => 1,
                'program' => 1,
                'event' => 1,
                'session' => 1,
                'user' => 1
            ];
            $session->entityType = ['proposal', 'measure', 'program', 'event', 'session', 'user'];
        }

        $sql = [];
        if (count($regSQL)) {
            $sql[] = '('.implode(' AND ', $regSQL).')';
        }
        if (count($levelSQL)) {
            $sql[] = '('.implode(' OR ', $levelSQL).')';
        }

        if (count($categorySQL)) {
            $sql[] = '('.implode(' OR ', $categorySQL).')';
        }

        $fromSQL = [];
        if ($entityType['proposal'] || $entityType['measure']) {
            $propType = [];
            if ($entityType['proposal']) {
                $propType[] = 'p.mes_id IS NULL';
            }
            if ($entityType['measure']) {
                $propType[] = 'p.mes_id IS NOT NULL';
            }
            $fromSQL[] =
                sprintf('select p.prop_uuid as uuid,  IF(p.mes_id is NULL, "proposal", "measure") as type, c.cat_image as img, CONCAT_WS(" - ", cc.cat_name, c.cat_name) as imgTitle, 
                            p.prop_saved_name as name, p.prop_description as description, 
                            p.prop_level as level, 
                            city.city_name as cityName, city.city_postalcode as postalCode, city.city_id as cityId, city.full_city_id fullCityId, 
                            region.region_name as regionName, region.region_id as regionId, 
                            country.country_name as countryName, country.country_code as countryCode,
                            p.prop_published_date as date, u.usr_name as username, u.usr_uuid as userUUID, u.usr_picture as userPicture,
                            cc.cat_id as mainCatId
                            from dgi_proposals p
                            left join dgi_cities city on p.city_id=city.city_id
                            left join dgi_regions region on city.region_id=region.region_id
                            left join dgi_countries country on city.country_id=country.country_id
                            left join dgi_categories c on p.cat_id=c.cat_id
                            left join dgi_categories cc on c.cat_id_cat=cc.cat_id
                            left join dgi_users u on p.usr_id=u.usr_id
                            WHERE p.prop_published_date IS NOT NULL AND p.prop_deleted_date IS NULL AND 
                            (%s) %s', implode(' OR ', $propType), count($sql)?'HAVING '.implode(' AND ', $sql):'')
            ;
        }

        if ($entityType['program']) {

            $fromSQL[] = sprintf('SELECT p.prog_uuid AS uuid, "program" AS type, NULL AS img, NULL AS imgTitle, 
                            p.prog_name AS name, p.prog_description AS description, 
                            p.prog_level AS level, 
                            city.city_name as cityName, city.city_postalcode as postalCode, city.city_id as cityId,  city.full_city_id fullCityId, 
                            region.region_name as regionName, region.region_id as regionId, 
                            country.country_name as countryName, country.country_code as countryCode,
                            p.prog_created_date AS date, u.usr_name AS username, u.usr_uuid AS userUUID, u.usr_picture AS userPicture,
                            -1 as mainCatId
                            FROM dgi_programs p
                            LEFT JOIN dgi_cities city ON p.city_id=city.city_id
                            LEFT JOIN dgi_regions region ON city.region_id=region.region_id
                            LEFT JOIN dgi_countries country ON city.country_id=country.country_id
                            LEFT JOIN dgi_users u ON p.usr_id=u.usr_id
                            WHERE p.prog_deleted_date IS NULL %s', count($sql)?'HAVING '.implode(' AND ', $sql):'')
            ;
        }
        if ($entityType['event'] || $entityType['session']) {
            $eventType = [];
            if ($entityType['event']) {
                $eventType[] = 'e.event_session=0';
            }
            if ($entityType['session']) {
                $eventType[] = 'e.event_session=1';
            }
            $fromSQL[] = sprintf('SELECT e.event_uuid AS uuid, IF(e.event_session=0, "event", "session") AS type, NULL AS img, NULL AS imgTitle, 
                            e.event_name AS name, e.event_description AS description, 
                            e.event_level AS level, 
                            city.city_name as cityName, city.city_postalcode as postalCode, city.city_id as cityId,  city.full_city_id fullCityId, 
                            region.region_name as regionName, region.region_id as regionId, 
                            country.country_name as countryName, country.country_code as countryCode,
                            e.event_start_date AS date, u.usr_name AS username, u.usr_uuid AS userUUID, u.usr_picture AS userPicture,
                            -1 as mainCatId
                            FROM dgi_events e
                            LEFT JOIN dgi_cities city ON e.city_id=city.city_id
                            LEFT JOIN dgi_regions region ON city.region_id=region.region_id
                            LEFT JOIN dgi_countries country ON city.country_id=country.country_id
                            LEFT JOIN dgi_users u ON e.usr_id=u.usr_id
                            WHERE e.event_canceled_date IS NULL AND e.event_published_date IS NOT NULL AND (%s) %s', implode(' OR ', $eventType), count($sql)?'HAVING '.implode(' AND ', $sql):'');
        }

        $sql = [];
        if (count($regSQL)) {
            $sql[] = '('.implode(' AND ', $regSQL).')';
        }
        if (count($levelUserSQL)) {
            $sql[] = '('.implode(' OR ', $levelUserSQL).')';
        }

        if (count($categorySQL)) {
            $sql[] = '('.implode(' OR ', $categorySQL).')';
        }

        if ($entityType['user']) {

            $fromSQL[] = sprintf('SELECT u.usr_uuid AS uuid, "user" AS type, u.usr_picture AS img, u.usr_name AS imgTitle, 
                            u.usr_name AS name, u.usr_presentation AS description, 
                            1 AS level, 
                            city.city_name as cityName, city.city_postalcode as postalCode, city.city_id as cityId,  city.full_city_id fullCityId, 
                            region.region_name as regionName, region.region_id as regionId, 
                            country.country_name as countryName, country.country_code as countryCode,
                            u.usr_registration_date AS date, NULL AS username, NULL AS userUUID, NULL AS userPicture,
                            -1 as mainCatId
                            FROM dgi_users u
                            LEFT JOIN dgi_cities city ON u.city_id=city.city_id
                            LEFT JOIN dgi_regions region ON city.region_id=region.region_id
                            LEFT JOIN dgi_countries country ON city.country_id=country.country_id 
                            WHERE u.usr_deleted_date IS NULL AND u.usr_active=1 AND u.usr_email_confirmed=1  %s', count($sql)?'HAVING '.implode(' AND ', $sql):'')
            ;
        }

        if (!count($fromSQL)) {
            /** @var $response \Zend\Http\Response */
            $response = $this->getResponse();
            $response->setStatusCode(400);
            $response->sendHeaders();
            exit;
        }

        $fromSQL = implode(' UNION ALL ', $fromSQL);

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('\DGIModule\Entity\SearchEntity', 'se');
        $rsm->addFieldResult('se', 'uuid', 'uuid');

        // get total results
        $query = $this->entityManager->createNativeQuery('SELECT uuid FROM ('.$fromSQL.') t', $rsm);
        $total = count($query->getScalarResult());

        $rsm->addEntityResult('\DGIModule\Entity\SearchEntity', 'se');
        $rsm->addFieldResult('se', 'type', 'type');
        $rsm->addFieldResult('se', 'img', 'img');
        $rsm->addFieldResult('se', 'imgTitle', 'imgTitle');
        $rsm->addFieldResult('se', 'name', 'name');
        $rsm->addFieldResult('se', 'description', 'description');
        $rsm->addFieldResult('se', 'date', 'date');
        $rsm->addFieldResult('se', 'username', 'username');
        $rsm->addFieldResult('se', 'userUUID', 'userUUID');
        $rsm->addFieldResult('se', 'userPicture', 'userPicture');
        $rsm->addFieldResult('se', 'level', 'level');
        $rsm->addFieldResult('se', 'cityName', 'cityName');
        $rsm->addFieldResult('se', 'postalCode', 'postalCode');
        $rsm->addFieldResult('se', 'regionName', 'regionName');
        $rsm->addFieldResult('se', 'countryName', 'countryName');

        $query = $this->entityManager->createNativeQuery(
            'SELECT uuid, type, img, imgTitle, name, description, date, username, userUUID, userPicture, 
            level,  cityName, postalCode, regionName, countryName
             FROM ('.$fromSQL.') t
             ORDER BY '.$sort.' '.$order.' LIMIT '.(int)$offset.','.(int)$limit, $rsm);

        $entities = $query->getResult();

        $viewModel = new ViewModel();
        $ajax = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($ajax);
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/index/search-results.mobile.phtml');
        }
        $viewModel->setVariables([
            'entities' => $entities,
            'totalEntities' => $total,
            'sort' => $sort,
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
            'filter' => $filter,
            'user' => $user,
            'entityType' => $entityType,
            'ajax' => $ajax,
            'list' => 'received',
            'keywords' => $session->keywords,
            'countries' => $countryList,
            'regions' => $regionList,
            'cities' => $cityList,
            'categories' => $categoryList,
        ]);
        return $viewModel;
    }

    
}

