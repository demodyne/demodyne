<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use DGIModule\Entity\User;

class DashboardController extends AbstractActionController
{
    public function cityDashboardAction()
    {
        $user = $this->identity();
        
        $config = $this->getServiceLocator()->get('Config');
        $session = new Container('level');
        
        if ($session->level == 'country' || $session->level == 'region') {
            unset($_SESSION['inbox']);
            unset($_SESSION['administrationDashboard']);
            unset($_SESSION['measure']);
            unset($_SESSION['event']);
            unset($_SESSION['news']);
            unset($_SESSION['newsletter']);
            unset($_SESSION['partnerDashboard']);
            unset($_SESSION['proposal']);
            unset($_SESSION['program']);
            unset($_SESSION['banner']);
        }
        
        $session->level = 'city';
        $session->levelValue = $config['demodyne']['level']['city'];
        
        
        if ($user && $user->isPartner()) {
            return $this->redirect()->toRoute('partner/dashboard');
        }
        
        $viewModel = new ViewModel();
        
        if ($user && $user->isAdministration()) {
            $administrationWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'administration-workspace'));
            $viewModel->addChild($administrationWorkspaceSection, 'workspaceSection');
        }
        elseif ($user && $user->isPartner()) {
            
        }
        else {
            $userWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'user-workspace'));
            $viewModel->addChild($userWorkspaceSection, 'workspaceSection');
        }
        
        $newsSection = $this->forward()->dispatch('DGIModule\Controller\News', array('action' => 'all-news'));
        $viewModel->addChild($newsSection, 'newsSection');
        
       $topProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'top-proposals'));
       $viewModel->addChild($topProposalsSection, 'topProposalsSection');
        
        $allProgramsSection = $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'all-programs'));
        $viewModel->addChild($allProgramsSection, 'allProgramsSection');
        
        $allProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'all-proposals'));
        $viewModel->addChild($allProposalsSection, 'allProposalsSection');
        
        $allMesuresSection = $this->forward()->dispatch('DGIModule\Controller\Measure', array('action' => 'all-measures'));
        $viewModel->addChild($allMesuresSection, 'allMesuresSection');
        
        $allEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'all-events'));
        $viewModel->addChild($allEventsSection, 'allEventsSection');
        
        // upcoming events section
        $upcomingEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'upcoming-events'));
        $viewModel->addChild($upcomingEventsSection, 'upcomingEventsSection');
        
        // carousel banner section
        $carouselBannersSection = $this->forward()->dispatch('DGIModule\Controller\Banner', array('action' => 'carousel-banners'));
        $viewModel->addChild($carouselBannersSection, 'carouselBannersSection');
        
        
        $viewModel->setVariables([
		    'user' => $user 
        ]);
        
		return $viewModel;
	}	
	
	public function regionDashboardAction()
	{
	    $user = $this->identity();
	
	    if (!$user && !isset($_SESSION['guest'])) {
	        return $this->forward()->dispatch('DGIModule\Controller\Index', ['action' => 'browse', 'country' => 'France']);
	    }
	
	    $viewModel = new ViewModel();
	
	    $config = $this->getServiceLocator()->get('Config');
	    $session = new Container('level');
	
	    if ($session->level == 'city' || $session->level == 'country') {
	        unset($_SESSION['inbox']);
	        unset($_SESSION['administrationDashboard']);
	        unset($_SESSION['measure']);
	        unset($_SESSION['event']);
	        unset($_SESSION['news']);
	        unset($_SESSION['newsletter']);
	        unset($_SESSION['partnerDashboard']);
	        unset($_SESSION['proposal']);
	        unset($_SESSION['program']);
	        unset($_SESSION['banner']);
	    }
	
	    $session->level = 'region';
	    $session->levelValue = $config['demodyne']['level']['region'];
	
	    if ($user && $user->isAdministration()) {
	        $administrationWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'administration-workspace'));
	        $viewModel->addChild($administrationWorkspaceSection, 'workspaceSection');
	    }
	    elseif ($user && $user->isPartner()) {
	    }
	    else {
	        $userWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'user-workspace'));
	        $viewModel->addChild($userWorkspaceSection, 'workspaceSection');
	    }
	
	    // carousel banner section
	    $carouselBannersSection = $this->forward()->dispatch('DGIModule\Controller\Banner', array('action' => 'carousel-banners'));
	    $viewModel->addChild($carouselBannersSection, 'carouselBannersSection');
	
	    // upcoming events section
	    $upcomingEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'upcoming-events'));
	    $viewModel->addChild($upcomingEventsSection, 'upcomingEventsSection');
	
	
	    $newsSection = $this->forward()->dispatch('DGIModule\Controller\News', array('action' => 'all-news'));
	    $viewModel->addChild($newsSection, 'newsSection');
	
	    $topProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'top-proposals'));
	    $viewModel->addChild($topProposalsSection, 'topProposalsSection');
	
	    $allProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'all-proposals'));
	    $viewModel->addChild($allProposalsSection, 'allProposalsSection');
	
	    $allProgramsSection = $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'all-programs'));
	    $viewModel->addChild($allProgramsSection, 'allProgramsSection');
	
	    $allMeasuresSection = $this->forward()->dispatch('DGIModule\Controller\Measure', array('action' => 'all-measures'));
	    $viewModel->addChild($allMeasuresSection, 'allMeasuresSection');
	
	    $allEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'all-events'));
	    $viewModel->addChild($allEventsSection, 'allEventsSection');
	
	    $viewModel->setVariables([
	        'user' => $user
	    ]);
	    
	    return $viewModel;
	}
	
	
	
	public function countryDashboardAction()
	{
	    $user = $this->identity();
	
	    if (!$user && !isset($_SESSION['guest'])) {
	        return $this->forward()->dispatch('DGIModule\Controller\Index', ['action' => 'browse', 'country' => 'France']);
	    }
	
	    $viewModel = new ViewModel();
	
	    $config = $this->getServiceLocator()->get('Config');
	    $session = new Container('level');
	
	    if ($session->level != 'country') {
	        unset($_SESSION['inbox']);
	        unset($_SESSION['administrationDashboard']);
	        unset($_SESSION['measure']);
	        unset($_SESSION['event']);
	        unset($_SESSION['news']);
	        unset($_SESSION['newsletter']);
	        unset($_SESSION['partnerDashboard']);
	        unset($_SESSION['proposal']);
	        unset($_SESSION['program']);
	        unset($_SESSION['banner']);
	    }
	
	    $session->level = 'country';
	    $session->levelValue = $config['demodyne']['level']['country'];
	
	    if ($user && $user->isAdministration()) {
	        $administrationWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'administration-workspace'));
	        $viewModel->addChild($administrationWorkspaceSection, 'workspaceSection');
	    }
	    elseif ($user && $user->isPartner()) {
	    }
	    else {
	        $userWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'user-workspace'));
	        $viewModel->addChild($userWorkspaceSection, 'workspaceSection');
	    }
	
	    // carousel banner section
	    $carouselBannersSection = $this->forward()->dispatch('DGIModule\Controller\Banner', array('action' => 'carousel-banners'));
	    $viewModel->addChild($carouselBannersSection, 'carouselBannersSection');
	
	    // upcoming events section
	    $upcomingEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'upcoming-events'));
	    $viewModel->addChild($upcomingEventsSection, 'upcomingEventsSection');
	
	
	    $newsSection = $this->forward()->dispatch('DGIModule\Controller\News', array('action' => 'all-news'));
	    $viewModel->addChild($newsSection, 'newsSection');
	
	    $topProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'top-proposals'));
	    $viewModel->addChild($topProposalsSection, 'topProposalsSection');
	
	    $allProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'all-proposals'));
	    $viewModel->addChild($allProposalsSection, 'allProposalsSection');
	
	    $allProgramsSection = $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'all-programs'));
	    $viewModel->addChild($allProgramsSection, 'allProgramsSection');
	
	    $allMeasuresSection = $this->forward()->dispatch('DGIModule\Controller\Measure', array('action' => 'all-measures'));
	    $viewModel->addChild($allMeasuresSection, 'allMeasuresSection');
	
	    $allEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'all-events'));
	    $viewModel->addChild($allEventsSection, 'allEventsSection');
	
	
	
	    $viewModel->setVariables([
	        'user' => $user
	    ]);
	    return $viewModel;
	}
	
}