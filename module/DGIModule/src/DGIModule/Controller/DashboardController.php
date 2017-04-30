<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class DashboardController extends AbstractActionController
{

    protected $entityManager;
    protected $config;

    public function __construct(
        array $config,
        EntityManager $entityManager
    )
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
    }

    public function dashboardAction()
    {
        $user = $this->identity();

        $level = $this->params()->fromRoute('level');

        $session = new Container('level');

        if ($session->level != $level) {
            $this->resetSession();
        }

        $session->level = $level;
        $session->levelValue = $this->config['demodyne']['level'][$level];

        if ($user && $user->isPartner()) {
            return $this->redirect()->toRoute('partner/dashboard');
        }

        $viewModel = new ViewModel();

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/dashboard/dashboard.mobile.phtml');
        }
        else {
            $viewModel->setTemplate('dgi-module/dashboard/dashboard.phtml');
            if ($user) {
                if ($user->isAdministration()) {
                    $administrationWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'administration-workspace'));
                    $viewModel->addChild($administrationWorkspaceSection, 'workspaceSection');
                } elseif ($user->isPartner()) {

                } else {
                    $userWorkspaceSection = $this->forward()->dispatch('DGIModule\Controller\AccountWorkspace', array('action' => 'user-workspace'));
                    $viewModel->addChild($userWorkspaceSection, 'workspaceSection');
                }
            }
        }

        $newsSection = $this->forward()->dispatch('DGIModule\Controller\News', array('action' => 'news'));
        $viewModel->addChild($newsSection, 'newsSection');

        $allProgramsSection = $this->forward()->dispatch('DGIModule\Controller\Program', array('action' => 'all-programs'));
        $viewModel->addChild($allProgramsSection, 'allProgramsSection');

        $allProposalsSection = $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action' => 'all-proposals'));
        $viewModel->addChild($allProposalsSection, 'allProposalsSection');

        $allMeasuresSection = $this->forward()->dispatch('DGIModule\Controller\Measure', array('action' => 'all-measures'));
        $viewModel->addChild($allMeasuresSection, 'allMeasuresSection');

        $allEventsSection = $this->forward()->dispatch('DGIModule\Controller\Event', array('action' => 'all-events'));
        $viewModel->addChild($allEventsSection, 'allEventsSection');

        $viewModel->setVariables([
            'user' => $user
        ]);

        return $viewModel;
    }

    public function administrationAction()
    {
        $session = new Container('level');
        /** @var \DGIModule\Entity\User $user */
        $user = $this->identity();
        if ($user && !$user->isAdministration()) {
            return $this->forward()->dispatch('DGIModule\Controller\Index', ['action' => 'index']);
        }
        $city = $user->getCity();
        $session->city = $city?$city->getCityId():null;

        return $this->forward()->dispatch('DGIModule\Controller\Dashboard',
            ['action' => 'dashboard',
                'level' => array_search($user->getAdmin()->getAdminLevel(), $this->config['demodyne']['level'])]);
    }

    public function cityAction()
    {
        $session = new Container('level');
        $user = $this->identity();
        $city = $user->getCity();
        $session->city = $city?$city->getCityId():null;
        return $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action' => 'dashboard', 'level' => 'city']);
    }

    public function regionAction()
    {
        $session = new Container('level');
        $user = $this->identity();
        $city = $user->getCity();
        $session->city = $city?$city->getCityId():null;
        return $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action' => 'dashboard', 'level' => 'region']);
    }

    public function countryAction()
    {
        $session = new Container('level');
        $user = $this->identity();
        $city = $user->getCity();
        $session->city = $city?$city->getCityId():null;
        return $this->forward()->dispatch('DGIModule\Controller\Dashboard', ['action' => 'dashboard', 'level' => 'country']);
    }

    private function resetSession() {
        unset($_SESSION['search']);
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
        unset($_SESSION['session']);
    }

    public function resetLevelAction()
    {
        unset($_SESSION['level']);
    }

}