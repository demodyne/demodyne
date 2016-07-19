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

class PagesController extends AbstractActionController
{
    public function aboutAction()
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
    
}
