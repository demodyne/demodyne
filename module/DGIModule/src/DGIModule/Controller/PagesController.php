<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class PagesController extends AbstractActionController
{

    public function __construct()
    {

    }

    public function aboutAction()
    {
        return new ViewModel();
    }
    
    public function testUiAction()
    {
        return new ViewModel();
    }
    
    public function helpAction()
    {
        
        $session = new Container('language');
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/pages/help-'.$session->language.'.phtml');
        
        return $viewModel;
    }
    public function termsAction()
    {
    $session = new Container('language');
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/pages/terms-'.$session->language.'.phtml');
        
        return $viewModel;
  
    }
    public function supportAction()
    {
        $session = new Container('language');
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/pages/support-'.$session->language.'.phtml');
        
        return $viewModel;
    }
    public function legalAction()
    {
    $session = new Container('language');
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/pages/legal-'.$session->language.'.phtml');
        
        return $viewModel;

    }
    public function browse1Action()
    {
        return new ViewModel();
    }
    
    public function pageAction()
    {
        $user = $this->identity();

        if (!$user) {
            unset($_SESSION['level']);
        }
        else {
            $levelSession = new Container('level');
            $levelSession->levelValue = null;
            $levelSession->level = null;
        }

        $page = $this->params()->fromRoute('page');
        $terminal = $this->params()->fromRoute('layout', 'on')=='on'?false:true;
        $session = new Container('language');

        $viewModel = new ViewModel();
        $viewModel->setTerminal($terminal);

        $mobile = isset($_SESSION['mobile']) && $_SESSION['mobile'];

        if ($mobile) {
            if (file_exists('./module/DGIModule/view/dgi-module/pages/'.$page.'-'.$session->language.'.mobile.phtml'))
                $viewModel->setTemplate('dgi-module/pages/'.$page.'-'.$session->language.'.mobile.phtml');
            elseif (file_exists('./module/DGIModule/view/dgi-module/pages/'.$page.'-en.mobile.phtml')) {
                $viewModel->setTemplate('dgi-module/pages/'.$page.'-en.mobile.phtml');
            }
            elseif (file_exists('./module/DGIModule/view/dgi-module/pages/'.$page.'.mobile.phtml')) {
                $viewModel->setTemplate('dgi-module/pages/'.$page.'.mobile.phtml');
            }
            elseif (file_exists('./module/DGIModule/view/dgi-module/pages/'.$page.'-'.$session->language.'.phtml'))
                $viewModel->setTemplate('dgi-module/pages/'.$page.'-'.$session->language.'.phtml');
            elseif (file_exists('./module/DGIModule/view/dgi-module/pages/'.$page.'-en.phtml')) {
                $viewModel->setTemplate('dgi-module/pages/'.$page.'-en.phtml');
            }
            elseif (file_exists('./module/DGIModule/view/dgi-module/pages/'.$page.'.phtml')) {
                $viewModel->setTemplate('dgi-module/pages/'.$page.'.phtml');
            }
            else {
                return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
            }

        }
        else {
            if (file_exists('./module/DGIModule/view/dgi-module/pages/' . $page . '-' . $session->language . '.phtml'))
                $viewModel->setTemplate('dgi-module/pages/' . $page . '-' . $session->language . '.phtml');
            elseif (file_exists('./module/DGIModule/view/dgi-module/pages/' . $page . '-en.phtml')) {
                $viewModel->setTemplate('dgi-module/pages/' . $page . '-en.phtml');
            } elseif (file_exists('./module/DGIModule/view/dgi-module/pages/' . $page . '.phtml')) {
                $viewModel->setTemplate('dgi-module/pages/' . $page . '.phtml');
            } else {
                return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
            }
        }
        return $viewModel;
    }


}
