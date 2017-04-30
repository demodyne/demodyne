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
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class AdminController extends AbstractActionController
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

    public function adminDigestAction()
    {

        $viewModel = new ViewModel();

        $from = $this->params()->fromRoute('from', null);
        $from = $from?new \DateTime($from.'00:00'):new \DateTime('2016-01-01');

        $to = $this->params()->fromRoute('to', null);
        $to = $to?new \DateTime($to.' 00:00'):new \DateTime();

        $users = $this->forward()->dispatch('DGIModule\Controller\Admin', array('action' => 'users', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), 'page'=>1));
        $viewModel->addChild($users, 'users');
        $usersCount = $this->entityManager->getRepository('DGIModule\Entity\User')->countPeriodUsers($from, $to);

        $proposals = $this->forward()->dispatch('DGIModule\Controller\Admin', array('action' => 'proposals', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), 'page'=>1));
        $viewModel->addChild($proposals, 'proposals');
        $proposalCount = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countPeriodProposals($from, $to);

        $measures = $this->forward()->dispatch('DGIModule\Controller\Admin', array('action' => 'measures', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), 'page'=>1));
        $viewModel->addChild($measures, 'measures');
        $measuresCount = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countPeriodProposals($from, $to, true);

        $events = $this->forward()->dispatch('DGIModule\Controller\Admin', array('action' => 'events', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), 'page'=>1));
        $viewModel->addChild($events, 'events');
        $eventsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countPeriodEvents($from, $to, false);

        $sessions = $this->forward()->dispatch('DGIModule\Controller\Admin', array('action' => 'sessions', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), 'page'=>1));
        $viewModel->addChild($sessions, 'sessions');
        $sessionsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countPeriodEvents($from, $to, true);

        $viewModel->setVariables([
            'proposalCount' => $proposalCount,
            'measuresCount' =>$measuresCount,
            'usersCount' => $usersCount,
            'eventsCount' => $eventsCount,
            'sessionsCount' => $sessionsCount,
            'from' => $from,
            'to' => $to
        ]);

        return $viewModel;

    }

    public function usersAction()
    {
        $user = $this->identity();

        $session = new Container('admin');

        $from = $this->params()->fromRoute('from', null);
        if (!$from) {
            if (!$session->usersFrom) {
                $from = new \DateTime('2016-01-01');
            }
            else {
                $from = new \DateTime($session->usersFrom);
            }
        }
        else {
            $from = new \DateTime($from);
        }
        $session->usersFrom = $from->format('Y-m-d');

        $to = $this->params()->fromRoute('to', null);
        if (!$to) {
            if (!$session->usersTo) {
                $to = new \DateTime();
            }
            else {
                $to = new \DateTime($session->usersTo);
            }
        }
        else {
            $to = new \DateTime($to);
        }
        $session->usersTo = $to->format('Y-m-d');

        $page = $this->params()->fromRoute('page', null);
        if (! $page) {
            if (! $session->usersPage) {
                $page = 1;
            }
            else {
                $page = $session->usersPage;
            }
        }
        $session->usersPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (! $sort) {
            if (! $session->usersSort) {
                $sort = 'start_date';
            }
            else {
                $sort = $session->usersSort;
            }
        }
        $session->usersSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (! $order) {
            if (! $session->usersOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->usersOrder;
            }
        }
        $session->usersOrder = $order;
        $limit = $this->params()->fromRoute('results', null);
        if (! $limit) {
            if (! $session->usersResults) {
                $limit = 5;
            }
            else {
                $limit = $session->usersResults;
            }
        }
        $session->usersResults = $limit;

        $usersCount = $this->entityManager->getRepository('DGIModule\Entity\User')->countPeriodUsers($from, $to);
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedUsers = $this->entityManager->getRepository('DGIModule\Entity\User')->getPeriodUsers($from, $to, $offset, $limit, $sort, $order);

        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'pagedUsers' => $pagedUsers,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'usersCount' => $usersCount,
        ]);
        return $viewModel;
    }


    /**
     * Shows the list of proposals for the searched period
     */
    public function proposalsAction() {

        $user = $this->identity();
        $session = new Container('admin');


        $from = $this->params()->fromRoute('from', null);
        if (!$from) {
            if (!$session->proposalsFrom) {
                $from = new \DateTime('2016-01-01');
            }
            else {
                $from = new \DateTime($session->proposalsFrom);
            }
        }
        else {
            $from = new \DateTime($from);
        }
        $session->proposalsFrom = $from->format('Y-m-d');

        $to = $this->params()->fromRoute('to', null);
        if (!$to) {
            if (!$session->proposalsTo) {
                $to = new \DateTime();
            }
            else {
                $to = new \DateTime($session->proposalsTo);
            }
        }
        else {
            $to = new \DateTime($to);
        }
        $session->proposalsTo = $to->format('Y-m-d');

        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->proposalsPage) {
                $page = 1;
            }
            else {
                $page = $session->proposalsPage;
            }
        }
        $session->proposalsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (!$sort) {
            if (!$session->proposalsSort) {
                $sort = 'published';
            }
            else {
                $sort = $session->proposalsSort;
            }
        }
        $session->proposalsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (!$order) {
            if (!$session->proposalsOrder) {
                $order = 'desc';
            }
            else {
                $order = $session->proposalsOrder;
            }
        }
        $session->proposalsOrder = $order;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->proposalsResults) {
                $limit = 5;
            }
            else {
                $limit = $session->proposalsResults;
            }
        }
        $session->proposalsResults = $limit;

        $proposalCount = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countPeriodProposals($from, $to);

        $viewModel = new ViewModel();
        $page =  ceil((float)$proposalCount/$limit) < $page ? ceil((float)$proposalCount/$limit) : $page; // Goto last page if page > last page
        $offset = $page>0?($page - 1) * $limit:0;

        $pagedProposals = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->getPeriodProposals($from, $to, false, $offset, $limit, $sort, $order);
        foreach ($pagedProposals as $index => $proposal) {
            $statusDetails =  $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action'=>'status-details', 'id' => $proposal->getPropUUID()));
            $viewModel->addChild($statusDetails, 'status-details-'.$index);
        }

        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'pagedProposals' => $pagedProposals,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'proposalCount' => $proposalCount,
        ]);
        return $viewModel;
    }

    /**
     * Shows the list of measures for the searched period
     */
    public function measuresAction() {

        $user = $this->identity();

        $session = new Container('admin');


        $from = $this->params()->fromRoute('from', null);
        if (!$from) {
            if (!$session->measuresFrom) {
                $from = new \DateTime('2016-01-01');
            }
            else {
                $from = new \DateTime($session->measuresFrom);
            }
        }
        else {
            $from = new \DateTime($from);
        }
        $session->measuresFrom = $from->format('Y-m-d');

        $to = $this->params()->fromRoute('to', null);
        if (!$to) {
            if (!$session->measuresTo) {
                $to = new \DateTime();
            }
            else {
                $to = new \DateTime($session->measuresTo);
            }
        }
        else {
            $to = new \DateTime($to);
        }
        $session->measuresTo = $to->format('Y-m-d');

        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->measuresPage) {
                $page = 1;
            }
            else {
                $page = $session->measuresPage;
            }
        }
        $session->measuresPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (!$sort) {
            if (!$session->measuresSort) {
                $sort = 'published';
            }
            else {
                $sort = $session->measuresSort;
            }
        }
        $session->measuresSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (!$order) {
            if (!$session->measuresOrder) {
                $order = 'desc';
            }
            else {
                $order = $session->measuresOrder;
            }
        }
        $session->measuresOrder = $order;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->measuresResults) {
                $limit = 5;
            }
            else {
                $limit = $session->measuresResults;
            }
        }
        $session->measuresResults = $limit;

        $proposalCount = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countPeriodProposals($from, $to, true);

        $viewModel = new ViewModel();
        $page =  ceil((float)$proposalCount/$limit) < $page ? ceil((float)$proposalCount/$limit) : $page; // Goto last page if page > last page
        $offset = $page>0?($page - 1) * $limit:0;
        $pagedProposals = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->getPeriodProposals($from, $to, true, $offset, $limit, $sort, $order);
        foreach ($pagedProposals as $index => $proposal) {
            $statusDetails =  $this->forward()->dispatch('DGIModule\Controller\Proposal', array('action'=>'status-details', 'id' => $proposal->getPropUUID()));
            $viewModel->addChild($statusDetails, 'status-details-'.$index);
        }

        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'pagedProposals' => $pagedProposals,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'proposalCount' => $proposalCount,
        ]);
        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function eventsAction()
    {
        $user = $this->identity();

        $session = new Container('admin');

        $from = $this->params()->fromRoute('from', null);
        if (!$from) {
            if (!$session->eventsFrom) {
                $from = new \DateTime('2016-01-01');
            }
            else {
                $from = new \DateTime($session->eventsFrom);
            }
        }
        else {
            $from = new \DateTime($from);
        }
        $session->eventsFrom = $from->format('Y-m-d');

        $to = $this->params()->fromRoute('to', null);
        if (!$to) {
            if (!$session->eventsTo) {
                $to = new \DateTime();
            }
            else {
                $to = new \DateTime($session->eventsTo);
            }
        }
        else {
            $to = new \DateTime($to);
        }
        $session->eventsTo = $to->format('Y-m-d');
        
        $page = $this->params()->fromRoute('page', null);
        if (! $page) {
            if (! $session->eventsPage) {
                $page = 1;
            }
            else {
                $page = $session->eventsPage;
            }
        }
        $session->eventsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (! $sort) {
            if (! $session->eventsSort) {
                $sort = 'start_date';
            }
            else {
                $sort = $session->eventsSort;
            }
        }
        $session->eventsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (! $order) {
            if (! $session->eventsOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->eventsOrder;
            }
        }
        $session->eventsOrder = $order;
        $limit = $this->params()->fromRoute('results', null);
        if (! $limit) {
            if (! $session->eventsResults) {
                $limit = 5;
            }
            else {
                $limit = $session->eventsResults;
            }
        }
        $session->eventsResults = $limit;

        $eventsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countPeriodEvents($from, $to, false);
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedEvents = $this->entityManager->getRepository('DGIModule\Entity\Event')->getPeriodEvents($from, $to, false, $offset, $limit, $sort, $order);

        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'pagedEvents' => $pagedEvents,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'eventsCount' => $eventsCount,
        ]);
        return $viewModel;
    }

    public function sessionsAction()
    {
        $user = $this->identity();

        $session = new Container('admin');

        $from = $this->params()->fromRoute('from', null);
        if (!$from) {
            if (!$session->sessionsFrom) {
                $from = new \DateTime('2016-01-01');
            }
            else {
                $from = new \DateTime($session->sessionsFrom);
            }
        }
        else {
            $from = new \DateTime($from);
        }
        $session->sessionsFrom = $from->format('Y-m-d');

        $to = $this->params()->fromRoute('to', null);
        if (!$to) {
            if (!$session->sessionsTo) {
                $to = new \DateTime();
            }
            else {
                $to = new \DateTime($session->sessionsTo);
            }
        }
        else {
            $to = new \DateTime($to);
        }
        $session->sessionsTo = $to->format('Y-m-d');

        $page = $this->params()->fromRoute('page', null);
        if (! $page) {
            if (! $session->sessionsPage) {
                $page = 1;
            }
            else {
                $page = $session->sessionsPage;
            }
        }
        $session->sessionsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (! $sort) {
            if (! $session->sessionsSort) {
                $sort = 'start_date';
            }
            else {
                $sort = $session->sessionsSort;
            }
        }
        $session->sessionsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (! $order) {
            if (! $session->sessionsOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->sessionsOrder;
            }
        }
        $session->sessionsOrder = $order;
        $limit = $this->params()->fromRoute('results', null);
        if (! $limit) {
            if (! $session->sessionsResults) {
                $limit = 5;
            }
            else {
                $limit = $session->sessionsResults;
            }
        }
        $session->sessionsResults = $limit;

        $sessionsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countPeriodEvents($from, $to, true);
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedSessions = $this->entityManager->getRepository('DGIModule\Entity\Event')->getPeriodEvents($from, $to, true, $offset, $limit, $sort, $order);

        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'pagedSessions' => $pagedSessions,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'sessionsCount' => $sessionsCount,
        ]);
        return $viewModel;
    }
}