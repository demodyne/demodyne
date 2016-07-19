<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use DGIModule\Form\AddEditProgramForm;
use DGIModule\Entity\Program;
use DGIModule\Entity\Comment;
use DGIModule\Entity\ProposalProgram;
use DGIModule\Entity\User;

class ProgramController extends AbstractActionController
{
    
    public function addProgramAction()
    {
        $user = $this->identity();
        $ajax = $this->params()->fromRoute('ajax', true);
        $level = $this->params()->fromRoute('level');
        if (!$level) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/program/add-edit-program.phtml');
        //disable layout if request by Ajax
        if ($ajax)  {
            $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        }
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $propUUID = $this->params('proposal');
        $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $propUUID));
        
        $config = $this->getServiceLocator()->get('Config');
        $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('usr' => $user, 'progLevel'=>$config['demodyne']['level'][$level], 'progDeletedDate'=>null));
        if ($program) {
            $viewModel->setVariables([
                'error' => 'errorProgramExists',
                'user' =>$user,
                'show' => ($proposal==null),
                'level' => $level
            ]);
            return $viewModel;
        }

        $form = new AddEditProgramForm();
        $form->setAttribute('action', $this->url()->fromRoute('program', array('action' => 'add-program', 'level' => $level,'proposal'=>$propUUID)));
        $progDescription = $this->params()->fromPost('progDescription');
        $request = $this->getRequest();
        if ($request->isPost()){
            $program = new Program();
            $form->bind($program);
            $form->setData($request->getPost());
            if ($form->isValid()){
                // search if name already exists for level
                $duplicateProgram = $entityManager->getRepository('DGIModule\Entity\Program')->searchProgramByName($user, $program->getProgName(), $level);
                if ($duplicateProgram) {
                    $form->get('progName')->setMessages([sprintf(_('There is alredy a program with this name published in your %s'), $level)]);
                }
                else {
                    // prepare data
                    $program->setProgCreatedDate(new \DateTime())
                            ->setProgSavedDate(new \DateTime())
                            ->setProgLevel($config['demodyne']['level'][$level])
                            ->setUsr($user)
                            ->setCity($user->getCity());
                    $entityManager->persist($program);
                
                    $entityManager->flush();
                    $entityManager->refresh($program);

                    if (!$proposal) {
                        $viewModel->setTemplate('dgi-module/program/add-success.phtml');
                        $viewModel->setVariables([
                            'program' => $program,
                        ]);
                        return $viewModel;
                    }
                    else {
                        return new \Zend\View\Model\JsonModel(array('success' => true, 'link'=>$this->url()->fromRoute('program', ['action'=>'add-proposal', 'id'=>$propUUID])));
                    }
                }
            }
        }
        $viewModel->setVariables([
            'form'=>$form,
            'error' => null,
            'user' =>$user,
            'ajax' => $ajax,
            'proposal' => $proposal,
            'program' => null,
            'progDescription' => $progDescription
        ]);
        return $viewModel;
    }
    
    public function editProgramAction()
    {
        $user = $this->identity();
        $progUUID = $this->params('id', '0');
        if (!$progUUID) return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $progUUID));
        // program doesn't exists
        if (!$program )  {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        // user is not the owner of the program
        if ($program->getUsr()!=$user) {
            return $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'view-program', 'id'=>$progUUID));
        }
        
        $config = $this->getServiceLocator()->get('Config');
        $level = array_search($program->getProgLevel(), $config['demodyne']['level']) ;
        
        $form = new AddEditProgramForm();
        $form->setAttribute('action', $this->url()->fromRoute('program', array('action' => 'edit-program', 'id'=>$progUUID)));
        
        $form->get('progName')->setValue($program->getProgName());
        $form->get('progDescription')->setValue($program->getProgDescription());
        $progDescription = $this->params()->fromPost('progDescription');
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->bind($program);
            $form->setData($request->getPost());
            if ($form->isValid()){
                
                // search if name already exists for level
                $duplicateProgram = $entityManager->getRepository('DGIModule\Entity\Program')->searchProgramByName($user, $program->getProgName(), $level);
                if ($duplicateProgram) {
                    $form->get('progName')->setMessages([sprintf(_('There is alredy a program with this name published in your %s'), $level)]);
                }
                else {
                    // prepare data
                    $program->setProgSavedDate(new \DateTime());
                    $entityManager->merge($program);
                    $entityManager->flush();
                    return new JsonModel(array('success' => true));
                }
            }
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/program/add-edit-program.phtml');
        $viewModel->setVariables([
            'program' => $program,
            'form' => $form,
            'user' => $user,
            'proposal' => null,
            'error' => null,
            'progDescription' => $progDescription
        ]);
        return $viewModel;
	}	
	
	public function viewProgramAction()
	{
	    $user = $this->identity();
	    $progUUID = $this->params()->fromRoute('id', '0');
	    if (!$progUUID)  {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $progUUID));
	    // program doesn't exists 
	    if (!$program) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action'=>'access-denied'));
	    }
	    
        $viewModel = new ViewModel();
	    $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
	    
	    $commentsSection = $this->forward()->dispatch('DGIModule\Controller\Comment', array('action'=>'add-comment', 'type' =>'program',  'id' => $progUUID, 'ajax' => false));
	    $viewModel->addChild($commentsSection, 'commentsSection');
	    
	    $proposalsSection = $this->forward()->dispatch('DGIModule\Controller\Program', array('action'=>'get-proposals', 'id' => $progUUID, 'ajax' => false));
	    $viewModel->addChild($proposalsSection, 'proposalsSection');
	    
	    $viewModel->setVariables([
	        'program' => $program,
	        'user' => $user,
	    ]);
	    return $viewModel;
	}
	
	public function viewAggregatedProgramAction()
	{
	    $user = $this->identity();
	    
	    $guestSession = new Container('guest');
	    if (!$user &&  !$guestSession->country) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    
	    if (!$user) {
	        $user = new User();
	        $user->setUsrId(0);
	    }
	    else {
	        $user = clone($user);
	    }
	    
	    if ($guestSession->country) {
	        $user->setCountry($entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $guestSession->country]));
	        if ($guestSession->city) {
	            $user->setCity($entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $guestSession->city]));
	        }
	    }
	    
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    
	    $limit = $this->params()->fromRoute('results');
	    $ajax = $this->params()->fromRoute('ajax', false);
	     
	    $aggregatedProposals = $entityManager->getRepository('DGIModule\Entity\Proposal')->getAggregatedProposals($user, $limit);
	    
	    $viewModel = new ViewModel();
	    if ($ajax) {
	       $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
	    }
	    
	    foreach ($aggregatedProposals as $index => $proposal) {
	        $status =  $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action'=>'status', 'id' => $proposal->getPropUUID()));
	        $viewModel->addChild($status, 'status-'.$index);
	    
	        $statusDetails =  $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action'=>'status-details', 'id' => $proposal->getPropUUID()));
	        $viewModel->addChild($statusDetails, 'status-details-'.$index);
	    }
	     
	    $viewModel->setVariables([
	        'aggregatedProposals' => $aggregatedProposals,
	        'limit' => $limit,
	        'user' => $user,
	    ]);
	    return $viewModel;
	}
	
	public function getProposalsAction() {
	    $user = $this->identity();
	    $ajax= $this->params()->fromRoute('ajax', true);
	    $progUUID = $this->params('id', '0');
	    
	    if (!$progUUID)  {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	     
	    $session = new Container('program');
	    if (!$session->getProposalUUID || $session->getProposalUUID!=$progUUID) {
	        $session->getProposalUUID = $progUUID;
	        $session->getProposalsPage = 1;
	        $session->getProposalsSort = 'priority';
	        $session->getProposalsOrder = 'asc';
	        $session->getProposalsResults = 5;
	    }
	    $page = $this->params()->fromRoute('page', null);
	    if (!$page) {
	        if (!$session->getProposalsPage) {
	            $page = 1;
	        }
	        else {
	            $page = $session->getProposalsPage;
	        }
	    }
	    $session->getProposalsPage = $page;
	    $sort = $this->params()->fromRoute('sort', null);
	    if (!$sort) {
	        if (!$session->getProposalsSort) {
	            $sort = 'priority';
	        }
	        else {
	            $sort = $session->getProposalsSort;
	        }
	    }
	    $session->getProposalsSort = $sort;
	    $order = $this->params()->fromRoute('order', null);
	    if (!$order) {
	        if (!$session->getProposalsOrder) {
	            $order = 'asc';
	        }
	        else {
	            $order = $session->getProposalsOrder;
	        }
	    }
	    $session->getProposalsOrder = $order;
	    $limit= $this->params()->fromRoute('results', null);
	    if (!$limit) {
	        if (!$session->getProposalsResults) {
	            $limit = 5;
	        }
	        else {
	            $limit = $session->getProposalsResults;
	        }
	    }
	    $session->getProposalsResults = $limit;
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $progUUID));
	    if (!$program)  {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $totalResults = count($program->getProposals());
	    $page = $limit!='all'? (ceil($totalResults/$limit) < $page ? ceil($totalResults/$limit) : $page) : $page; // @todo Goto last page if page > last page
        $viewModel = new ViewModel();
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedProposals = $entityManager->getRepository('DGIModule\Entity\ProposalProgram')->getProgramPagedProposals($program, $offset, $limit, $sort, $order);
        if ($ajax) {
            $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        }
        $viewModel->setVariables([
           'pagedProposals' => $pagedProposals,
           'limit' => $limit,
           'page' => $page,
           'sort' => $sort,
           'order' => $order,
           'user' => $user,
           'program' => $program,
		   'owner' => $user==$program->getUsr(),
        ]);
        return $viewModel;
	}
	
	private function sortProposals($entityManager, $program) {
	    $proposals = $entityManager->getRepository('DGIModule\Entity\Proposal')->getProgramPagedSortProposals($program);
	    foreach ($proposals as $index => $proposal) {
	        $proposalProgram = $entityManager->getRepository('DGIModule\Entity\ProposalProgram')->findOneBy(array('prop' => $proposal, 'prog'=>$program));
	        $proposalProgram->setSortPosition($index+1);
	        $entityManager->merge($proposalProgram);
	    }
	    $entityManager->flush();
	    
	}
	
	private function proposalAggregatedScore($proposal) {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $config = $this->getServiceLocator()->get('Config');
	    $priority = $config['demodyne']['priority'];
	    $score = 0;
        $voteAverage = $proposal->getVotesAverage();
        if ($voteAverage>=$config['demodyne']['vote']['average']) {
            $included = false;
            foreach ($proposal->getProposalPrograms() as $proposalProgram) {
                $included = true;
                $position = $proposalProgram->getSortPosition();
	            $score += $position<11?$priority[$position]:$priority[11];
	        }
	        if ($included) {
    	        $score += 100;
    	        $topScore = $entityManager->getRepository('DGIModule\Entity\Vote')->getTopValue($proposal, $config['demodyne']['level']);
    	        $score = $topScore['toppoints'] * $score;
	        }
        }
        $proposal->setPropAggregatedScore($score);
        $entityManager->merge($proposal);
	    $entityManager->flush();
	}
	
	private function programAggregatedScore($program) {
	   foreach ($program->getProposals() as $proposal) {
	       $this->proposalAggregatedScore($proposal);
	   }
	   
	}
	
	public function sortProposalsAction() {
	    $user = $this->identity();
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $progUUID = $this->params()->fromRoute('id', '0');
	    if (!$progUUID)  {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $progUUID));
	    if (!$program || $program->getUsr()!=$user)  {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $viewModel = new ViewModel();
	     $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
	     $request = $this->getRequest();
        if ($request->isPost()){
            $sortedProposals = $this->params()->fromPost('sort-proposals');
            foreach ($sortedProposals as $index => $propUUID) {
                $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $propUUID));
                $proposalProgram = $entityManager->getRepository('DGIModule\Entity\ProposalProgram')->findOneBy(array('prop' => $proposal, 'prog'=>$program));
                $proposalProgram->setSortPosition($index+1);
                $entityManager->merge($proposalProgram);
            }
            $program->setProgSavedDate(new \DateTime());
            $entityManager->merge($program);
            $entityManager->flush();
            $this->programAggregatedScore($program);
            return new \Zend\View\Model\JsonModel(array('success' => true));
        }
        $proposals = $entityManager->getRepository('DGIModule\Entity\Proposal')->getProgramPagedSortProposals($program);
	    $viewModel->setVariables([
	        'proposals' => $proposals, 
	        'user' => $user,
	        'program' => $program,
	        'owner' => $user==$program->getUsr(),
	    ]);
	    return $viewModel;
	}
	
	public function getCategoriesCountAction()
	{
	    $user = $this->identity();
	    $progUUID = $this->params('id', '0');
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $progUUID));
	    if ( !$program  ) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $categories = [];
	    $counts = [];
	    $names = [];
	    foreach ($program->getProposals() as $proposal) {
	        if (!isset($categories[$proposal->getCat()->getCatCat()->getCatId()])) {
	            $counts[$proposal->getCat()->getCatCat()->getCatId()] = 0;
	            $names[$proposal->getCat()->getCatCat()->getCatId()] = $proposal->getCat()->getCatCat()->getCatName();
	            $categories[$proposal->getCat()->getCatCat()->getCatId()] = $proposal->getCat()->getCatCat();
	        }
	        $counts[$proposal->getCat()->getCatCat()->getCatId()]++;
	    }
	    array_multisort($counts, SORT_DESC, SORT_NUMERIC, $names, SORT_ASC, SORT_STRING, $categories);
	    $viewModel = new ViewModel();
	    $viewModel->setVariables([
	        'categories' => $categories,
	        'counts'   => $counts
	    ]);
	    return $viewModel;
	}
	
	public function myProgramsAction()
	{
	    $user = $this->identity();
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $viewModel = new ViewModel();
	    $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());

	    $cityProgram = null;
	    $regionProgram = null;
	    $countryProgram = null;
	    
	    $config = $this->getServiceLocator()->get('Config');
	    
	    $myPrograms = $entityManager->getRepository('DGIModule\Entity\Program')->getUserPrograms($user);
	    foreach ($myPrograms as $index => $program) {
	        $categories= $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'get-categories-count', 'id' => $program->getProgUUID()));
	        $viewModel->addChild($categories, 'categories-'.$program->getProgLevel());
	        
	        if ($program->getProgLevel()==$config['demodyne']['level']['city']) {
	            $cityProgram = $program;
	        }
	        elseif($program->getProgLevel()==$config['demodyne']['level']['region']) {
	            $regionProgram = $program;
	        }
	        else {
	            $countryProgram = $program;
	        }
	         
	    }
	    $myPrograms = [];
	    $myPrograms['city'] = $cityProgram;
	    $myPrograms['region'] = $regionProgram;
	    $myPrograms['country'] = $countryProgram;
	    
	    $viewModel->setVariables([
	        'myPrograms' => $myPrograms,
	        'user' => $user
	    ]);
	    return $viewModel;
	}
	
	public function userProgramsAction()
	{

        $usrUUID = $this->params('id', '0');
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $user = $entityManager->getRepository('DGIModule\Entity\User')->findOneBy(array('usrUUID' => $usrUUID, 'usrDeletedDate'=>null));
	    if (!$user) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }

	    $programs = $entityManager->getRepository('DGIModule\Entity\Program')->getUserPrograms($user);
	    
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());

	    $cityProgram = null;
	    $regionProgram = null;
	    $countryProgram = null;
	    
	    $config = $this->getServiceLocator()->get('Config');
	     
	    $userPrograms = [];
	     
	    foreach ($programs as $index => $program) {
	        $categories= $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'get-categories-count', 'id' => $program->getProgUUID()));
	        $viewModel->addChild($categories, 'categories-'.$program->getProgLevel());
	     
	        $userPrograms[$program->getProgLevel()] = $program; 
	    }
	    
	    ksort($userPrograms);
	    $viewModel->setVariables([
	        'programs' => $userPrograms,
	        'user' => $user
	    ]);
	    return $viewModel;
	}
		
	/**
	 * Add a proposal to a program 
	 */
	public function addProposalAction()
	{
	    $user = $this->identity();
	    $propUUID = $this->params('id');
	    // no parameter
	    if (!$propUUID) {
	        //return $this->redirect()->toUrl($this->getRequest()->getHeader('HTTP_REFERER'));
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'error', 'dialog' => $this->getRequest()->isXmlHttpRequest(), 
                       'message' => _('There is no Proposal provided.')));
	    }
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->getProposalByUUID($propUUID);
        // proposal doesn't exists 
        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'error', 'dialog' => $this->getRequest()->isXmlHttpRequest(), 
                       'message' => _('The provided Proposal does not exists.')));
        }
        
        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $configItem = $viewHelperManager->get('configItem');
        
        $sameLevel = false;
        if ($proposal->getPropLevel()==$configItem('demodyne.level.city') && ( $proposal->getCity()==$user->getCity() || $proposal->getCity()==$user->getCity()->getFullCity() || ($proposal->getCity()->getDistrictCode()==$user->getCity()->getDistrictCode() && $proposal->getCity()->getFullCity()==$user->getCity()->getFullCity()) || ($proposal->getPropFullCity() && $proposal->getCity()->getFullCity()==$user->getCity()->getFullCity()))) {
	        $sameLevel = true;
	    }
	    if ($proposal->getPropLevel()==$configItem('demodyne.level.region') && $user->getCity()->getRegion()==$proposal->getCity()->getRegion()) {
	        $sameLevel = true;
	    }
	    if ($proposal->getPropLevel()==$configItem('demodyne.level.country') && $user->getCountry()==$proposal->getCity()->getCountry()) {
	        $sameLevel = true;
	    }
        
        if (!$sameLevel) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', 
                   array('action' => 'error', 
                       'dialog' => $this->getRequest()->isXmlHttpRequest(), 
                       'message' => _('You cannot add the proposal to your program.')));
        }
        
        $program = $user->getProgramForLevel($proposal->getPropLevel());
        
        if ($program) {
            $request = $this->getRequest();
            if ($request->isPost()){
                $program->setProgSavedDate(new \DateTime());
                $entityManager->merge($program);
                $proposalProgram = new ProposalProgram();
                $proposalProgram->setProp($proposal)
                                ->setProg($program)
                                ->setSortPosition(count($program->getProposals())+1);
                $entityManager->persist($proposalProgram);
                $entityManager->flush();
                $entityManager->refresh($program);
                $this->sortProposals($entityManager, $program);
                $this->programAggregatedScore($program);
                $viewModel->setTemplate('dgi-module/program/add-proposal-success.phtml');
                $viewModel->setVariables([
                    'proposal' => $proposal,
                    'program' => $program,
                ]);
                return $viewModel;
            }
        }

        $viewModel->setVariables([
            'proposal' => $proposal,
            'program' => $program,
            'user' => $user
        ]);
        return $viewModel;
	}
	
	public function addProposalsFromCityAction() {
	    $user = $this->identity();
	    $progUUID = $this->params('id', '0');
	    
	    $session = new Container('program');
	    if (!$session->addProposalsFromCityUUID || $session->addProposalsFromCityUUID!=$progUUID) {
	        $session->addProposalsFromCityUUID = $progUUID;
	        $session->addProposalsFromCitysPage = 1;
	        $session->addProposalsFromCitysSort = 'name';
	        $session->addProposalsFromCitysOrder = 'asc';
	        $session->addProposalsFromCitysResults = 5;
	    }
	    $page = $this->params()->fromRoute('page', null);
	    if (!$page) {
	        if (!$session->addProposalsFromCitysPage) {
	            $page = 1;
	        }
	        else {
	            $page = $session->addProposalsFromCitysPage;
	        }
	    }
	    $session->addProposalsFromCitysPage = $page;
	    $sort = $this->params()->fromRoute('sort', null);
	    if (!$sort) {
	        if (!$session->addProposalsFromCitysSort) {
	            $sort = 'name';
	        }
	        else {
	            $sort = $session->addProposalsFromCitysSort;
	        }
	    }
	    $session->addProposalsFromCitysSort = $sort;
	    $order = $this->params()->fromRoute('order', null);
	    if (!$order) {
	        if (!$session->addProposalsFromCitysOrder) {
	            $order = 'asc';
	        }
	        else {
	            $order = $session->addProposalsFromCitysOrder;
	        }
	    }
	    $session->addProposalsFromCitysOrder = $order;
	    $limit= $this->params()->fromRoute('results', null);
	    if (!$limit) {
	        if (!$session->addProposalsFromCitysResults) {
	            $limit = 5;
	        }
	        else {
	            $limit = $session->addProposalsFromCitysResults;
	        }
	    }
	    $session->addProposalsFromCitysResults = $limit;
	    
	    $filter= $this->params()->fromRoute('filter', null);
	    if (!$filter) {
	        if (!$session->addProposalsFromCitysFilter) {
	            $filter = 'none';
	        }
	        else {
	            $filter = $session->addProposalsFromCitysFilter;
	        }
	    }
	    $session->addProposalsFromCitysFilter = $filter;
	    
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $proposalCount = $entityManager->getRepository('DGIModule\Entity\Proposal')->countAllPublishedProposalsAndMeasures($user);
	    $proposalCount = $proposalCount["totalProposals"];
	    $totalResults = $proposalCount;
	    $page =  ceil((float)$totalResults/$limit) < $page ? ceil((float)$totalResults/$limit) : $page; // @todo Goto last page if page > last page
	    $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $progUUID));
	    // program doesn't exists or the logged user is not the owner of the program
	    if (!$program || $program->getUsr()!=$user) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $viewModel = new ViewModel();
	    $offset = ($limit == 'all') ? 0 : ($page - 1) * $limit;
	    $config = $this->getServiceLocator()->get('Config');
	    
	    $pagedProposals = $entityManager->getRepository('DGIModule\Entity\Proposal')->getCityPagedProposalsForProgram($program, $offset, $limit, $sort, $order, $filter, $config['demodyne']['level']);
	    
	    foreach ($pagedProposals as $index => $proposal) {
	        $status =  $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action'=>'status-details', 'id' => $proposal->getPropUUID()));
	        $viewModel->addChild($status, 'status-'.$index);
	    }
	    
	    $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
	    $viewModel->setVariables([
	        'pagedProposals' => $pagedProposals,
	        'limit' => $limit,
	        'page' => $page,
	        'sort' => $sort,
	        'order' => $order,
	        'filter' => $filter,
	        'proposalCount' => $proposalCount,
	        'program' => $program
	    ]);
	    return $viewModel;
	}
	
	/**
	 *  Add/Remove a proposal to/from a program
	 */
	public function addRemoveProposalAction()
	{
	    $user = $this->identity();
	    $propUUID = $this->params('id');
	    // no parameter
	    if (!$propUUID) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $propUUID));
	    // proposal doesn't exists 
	    if (!$proposal) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $program = $user->getProgramForLevel($proposal->getPropLevel());
	    // program doesn't exists 
	    if (!$program) {
	        return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
	    }
	    $added = false;
	    $removed = false;
	    $proposalProgram = $entityManager->getRepository('DGIModule\Entity\ProposalProgram')->findOneBy(array('prop' => $proposal, 'prog' => $program));
	    if ($proposalProgram) {
	        $entityManager->remove($proposalProgram); 
	        $removed = true;
	    }
	    else {
	        $proposalProgram = new ProposalProgram();
	        $proposalProgram->setProp($proposal)
                            ->setProg($program)
                            ->setSortPosition(count($program->getProposals())+1); // @todo sort again the proposals
	        $entityManager->persist($proposalProgram);
	        $added = true;
	    }
	    $program->setProgSavedDate(new \DateTime());
        $entityManager->merge($program);
        $entityManager->flush();
        $entityManager->refresh($program);$entityManager->refresh($proposal);
        $this->sortProposals($entityManager, $program);
        $this->programAggregatedScore($program);
        $this->proposalAggregatedScore($proposal);
        return new JsonModel(array('added' => $added, 'removed'=>$removed));
	}
    
    /**
     * Delete a program
     */
    public function deleteProgramAction()
    {
        $user = $this->identity(); 
        $progUUID = $this->params('id');
        // no parameter
        if (!$progUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $progUUID));
        // program doesn't exists or the logged user is not the owner of the program
        if (!$program || ($program->getUsr()!=$user && $user->getUsrlId()!=$config['demodyne']['account']['type']['admin'])) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $request = $this->getRequest();
        if ($request->isPost()){
            // if already deleted
            if ($program->getProgDeletedDate()) {
                return new \Zend\View\Model\JsonModel(array('success' => true));
            }
            $program->setProgDeletedDate(new \DateTime())
                    ->setProgSavedName($program->getProgName())
                    ->setProgName($program->getProgUUID());
            $entityManager->merge($program);
            $entityManager->flush();
            return new \Zend\View\Model\JsonModel(array('success' => true));
        }
        $viewmodel = new ViewModel();
        //disable layout if request by Ajax
        $viewmodel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewmodel->setVariables([
            'program' => $program
        ]);
        return $viewmodel;
    }
    
    public function allProgramsAction() {
        $user = $this->identity();
        
        $guestSession = new Container('guest');
        if (!$user &&  !$guestSession->country) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        if (!$user) {
            $user = new User();
            $user->setUsrId(0);
            
        }
        else {
            $user = clone($user);
        }
        
        if ($guestSession->country) {
            $user->setCountry($entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $guestSession->country]));
            if ($guestSession->city) {
                $user->setCity($entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $guestSession->city]));
            }
        }
        
        $session = new Container('program');
        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->allProgramsPage) {
                $page = 1;
            }
            else {
                $page = $session->allProgramsPage;
            }
        }
        $session->allProgramsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (!$sort) {
            if (!$session->allProgramsSort) {
                $sort = 'name';
            }
            else {
                $sort = $session->allProgramsSort;
            }
        }
        $session->allProgramsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (!$order) {
            if (!$session->allProgramsOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->allProgramsOrder;
            }
        }
        $session->allProgramsOrder = $order;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->allProgramsResults) {
                $limit = 5;
            }
            else {
                $limit = $session->allProgramsResults;
            }
        }
        $session->allProgramsResults = $limit;
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $programsCount = $entityManager->getRepository('DGIModule\Entity\Program')->countPrograms($user);

        $page = ceil($programsCount/$limit) < $page ? ceil($programsCount/$limit) : $page; // @todo Goto last page if page > last page
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;

        $pagedPrograms = $entityManager->getRepository('DGIModule\Entity\Program')->getPagedPrograms($user, $offset, $limit, $sort, $order);
        
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        foreach ($pagedPrograms as $index => $program) {
            $categories= $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'get-categories-count', 'id' => $program->getProgUUID()));
            $viewModel->addChild($categories, 'categories-'.$index);
        }
        
        // get proposals for aggregated program
        $aggregatedProgram= $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'view-aggregated-program', 'results' => 3));
        $viewModel->addChild($aggregatedProgram, 'aggregatedProgram');
        
        $viewModel->setVariables([
            'pagedPrograms' => $pagedPrograms,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'programsCount' => $programsCount
        ]);
        return $viewModel;
    }
}