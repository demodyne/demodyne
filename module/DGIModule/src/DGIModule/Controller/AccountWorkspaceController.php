<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AccountWorkspaceController extends AbstractActionController
{
    
    public function userWorkspaceAction()
    {
        $user = $this->identity();
        $viewModel = new ViewModel();
    
        $inboxSection = $this->forward()->dispatch('DGIModule\Controller\Inbox', array('action' => 'my-inbox'));
        $viewModel->addChild($inboxSection, 'inboxSection');
        $myProgramsSection = $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'my-programs'));
        $viewModel->addChild($myProgramsSection, 'myProgramsSection');
    
        $myProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'my-proposals'));
        $viewModel->addChild($myProposalsSection, 'myProposalsSection');
        $favoritesSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'my-favorites'));
        $viewModel->addChild($favoritesSection, 'favoritesSection');
        $contactSection = $this->forward()->dispatch('DGIModule\Controller\Inbox', array('action' => 'my-contacts'));
        $viewModel->addChild($contactSection, 'contactsSection');
        $viewModel->setVariables([
            'user' => $user
        ]);
        return $viewModel;
    }
    
    
    public function administrationWorkspaceAction()
	{
	    $user = $this->identity();
	    $lang = $this->params('lang', 'en');
	    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    //var_dump($votes);
	    $viewModel = new ViewModel();
	    
	    $inboxSection = $this->forward()->dispatch('DGIModule\Controller\Inbox', array('action' => 'my-inbox'));
	    $viewModel->addChild($inboxSection, 'inboxSection');
	    
	    $favoritesSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'my-favorites'));
	    $viewModel->addChild($favoritesSection, 'favoritesSection');
	    
	    // banner section
	    $myBannersSection = $this->forward()->dispatch('DGIModule\Controller\Banner', array('action' => 'my-banners'));
	    $viewModel->addChild($myBannersSection, 'myBannersSection');
	    
	    // event section
	    $myEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'my-events'));
	    $viewModel->addChild($myEventsSection, 'myEventsSection');
	    
	    // newsletter section
	    $myNewslettersSection = $this->forward()->dispatch('DGIModule\Controller\Newsletter', array('action' => 'my-newsletters'));
	    $viewModel->addChild($myNewslettersSection, 'myNewslettersSection');
	    
	    // draft measures section
	    $draftMesuresSection = $this->forward()->dispatch('DGIModule\Controller\Measure', array('action' => 'draft-measures'));
	    $viewModel->addChild($draftMesuresSection, 'draftMesuresSection');
	    
	    // my contacts
	    $contactSection = $this->forward()->dispatch('DGIModule\Controller\Inbox', array('action' => 'my-contacts'));
	    $viewModel->addChild($contactSection, 'contactsSection');
	    
	    $viewModel->setVariables([
	        'user' => $user
	    ]);
	    return $viewModel;
	}
	
	

}