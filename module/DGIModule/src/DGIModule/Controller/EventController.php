<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use DGIModule\Form\SessionAddEditForm;
use Zend\Filter\File\Rename;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use DGIModule\Entity\Event;
use DGIModule\Form\AddEditEventForm;
use DGIModule\Entity\User;
use DGIModule\Entity\Inbox;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class EventController extends AbstractActionController
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
    
    public function addEventAction()
    {
        $user = $this->identity();
        $publish = $this->params()->fromRoute('publish', false);
        
        $session = new Container('level');
        $level = $session->level;
        
        $event = new Event();

        $form = new AddEditEventForm($this->translator);
        $form->setAttribute('action', $this->url()->fromRoute('event', array('action'=>'add-event')));
        
        $request = $this->getRequest();
        $eventDescription = $this->params()->fromPost('eventDescription');
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->bind($event);
            $form->setData($post);
            $picture = $post["eventImage"]["name"];
            // TODO verify file size
            if ($form->isValid()) {
                if ($picture != "") {
                    $files = $request->getFiles();
                    $target = getcwd() . "/public/img/events/event.jpg";
                    $filterR = new Rename(array(
                        "target" => $target,
                        "randomize" => true
                    ));
                    $filename = $filterR->filter($files['eventImage']);
                    chmod($filename["tmp_name"], 0644);
                    $event->setEventImage(str_replace(getcwd() . "/public", '', $filename["tmp_name"]));
                } else {
                    $event->setEventImage(null);
                }
                $event->setUsr($user)
                      ->setEventCreatedDate(new \DateTime())
                      ->setEventUUID(Uuid::uuid4())
                ;
                
                if ($user->isAdministration()) {
                      $event->setEventLevel($user->getAdmin()->getAdminLevel());
                      if ($level=='city' && $user->getAdmin()->getAdminCity()) {
                          $event->setCity($user->getAdmin()->getAdminCity());
                      }
                      else {
                          $event->setCity($user->getCity());
                      }
                }
                
                if ($publish) {
                    $event->setEventPublishedDate(new \DateTime());
                }
                $this->entityManager->persist($event);
                $this->entityManager->flush();

                if ($event->getEventPublishedDate()) {
                    $this->forward()->dispatch('DGIModule\Controller\News', array('action' => 'create-news', 'id' => $event->getEventId(), 'type' => 'new_public_event'));
                }

                return new JsonModel(array(
                    'success' => true
                ));
            }
        }
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/event/add-edit-event.phtml');
        $viewModel->setVariables([
            'form' => $form,
            'user' => $user,
            'eventDescription' => $eventDescription
        ]);
        return $viewModel;
    }
    public function editEventAction()
    {
        $user = $this->identity();
        setlocale(LC_ALL, $user->getCountry()->getCountryFormat());
        $publish = $this->params()->fromRoute('publish', false);
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $eventUUID]);
        // event doesn't exists or the logged user is not the owner of the event
        if (! $event || $event->getUsr() != $user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
        $form = new AddEditEventForm($this->translator);
        $form->setAttribute('action', $this->url()->fromRoute('event', array('action'=>'edit-event', 'id'=>$eventUUID)));
        $oldFilename = $event->getEventImage();
        $request = $this->getRequest();
        $eventDescription = $this->params()->fromPost('eventDescription');
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->bind($event);
            $form->setData($post);
            $picture = $post["eventImage"]["name"];
            if ($form->isValid()) {
                if ($picture != "") {
                    $files = $request->getFiles();
                    $target = getcwd() . "/public/img/events/event.jpg";
                    $filterR = new Rename(array(
                        "target" => $target,
                        "randomize" => true
                    ));
                    $filename = $filterR->filter($files['eventImage']);
                    chmod($filename["tmp_name"], 0644);
                    if ($oldFilename && file_exists(getcwd() . '/public' . $oldFilename)) {
                        unlink(getcwd() . '/public' . $oldFilename);
                    }
                    $event->setEventImage(str_replace(getcwd() . "/public", '', $filename["tmp_name"]));
                } else {
                    $event->setEventImage($oldFilename);
                }
                if ($publish) {
                    $event->setEventPublishedDate(new \DateTime());
                }
                $this->entityManager->merge($event);
                $this->entityManager->flush();
                return new JsonModel(array(
                    'success' => true
                ));
            }
        }
        else {
            $form->get('eventName')->setValue($event->getEventName());
            $form->get('eventDescription')->setValue($event->getEventDescription());
            $form->get('eventLink')->setValue($event->getEventLink());
            $form->get('eventLocation')->setValue($event->getEventLocation());
        }
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/event/add-edit-event.phtml');
        $viewModel->setVariables([
            'form' => $form,
            'user' => $user,
            'event' => $event,
            'eventDescription' => $eventDescription
        ]);
        return $viewModel;
    }
    public function duplicateEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));

        /** @var \DGIModule\Entity\Event $event */
        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        if (! $event || $event->getUsr() != $user)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));

        $viewModel = new ViewModel();

        if ($event->isSession()) {
            $form = new SessionAddEditForm($this->entityManager, $event->getCity());
            $form->setAttribute('action', $this->url()->fromRoute('session', array('action' => 'add-edit-session')));
            $viewModel->setTemplate('dgi-module/session/add-edit-session.phtml');

            $form->get('eventName')->setValue($event->getEventName());
            $form->get('eventLink')->setValue($event->getEventLink());
            $form->get('eventDate')->setValue($event->getEventStartDate()->setTimezone(new \DateTimeZone($event->getCity()->getRegion()->getRegionTimezone()))->format('Y-m-d'));
            $form->get('eventTime')->setValue($event->getEventStartDate()->setTimezone(new \DateTimeZone($event->getCity()->getRegion()->getRegionTimezone()))->format('H:i'));
            $form->get('eventDuration')->setValue($event->getEventStartDate()->diff($event->getEventEndDate())->format('%H:%I'));
            $form->get('eventLocation')->setValue($event->getEventLocation());
            $viewModel->setVariables([
                'form' => $form,
                'user' => $user,

                'level' => array_search($event->getEventLevel(), $this->config['demodyne']['level']),
            ]);
        }
        else {
            $form = new AddEditEventForm();
            $form->setAttribute('action', $this->url()->fromRoute('event', array('action' => 'add-event')));
            $viewModel->setTemplate('dgi-module/event/add-edit-event.phtml');

            $form->get('eventName')->setValue($event->getEventName());
            $form->get('eventDescription')->setValue($event->getEventDescription());
            $form->get('eventLink')->setValue($event->getEventLink());
            $form->get('eventLocation')->setValue($event->getEventLocation());
        }

        $request = $this->getRequest();
        $viewModel->setTerminal($request->isXmlHttpRequest());

        $viewModel->setVariables([
            'form' => $form,
            'user' => $user,
            'eventDescription' => $event->getEventDescription(),
            'event' => $event
        ]);
        return $viewModel;
    }

    public function deleteEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (!$eventUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied', 'dialog'=>true]);
        }
        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $eventUUID]);
        // event not exists or user not owner
        if (!$event || $event->getUsr()!=$user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied', 'dialog'=>true]);
        }
        $request = $this->getRequest();
        if ($request->isPost()){
            $oldFilename = $event->geteventImage();
            if ($oldFilename && file_exists(getcwd() . '/public' . $oldFilename)) {
                unlink(getcwd() . '/public' . $oldFilename);
            }
            $this->entityManager->remove($event);
            $this->entityManager->flush();
            return new JsonModel(array('success' => true));
        }
        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setVariables([
            'event' => $event,
        ]);
        return $viewModel;
    }

    public function myEventsAction()
    {
        $user = $this->identity();
        $month = $this->params()->fromRoute('month', null);
        $drafts = $this->params()->fromRoute('publish', false);
        $year = $this->params()->fromRoute('year', null);
        $searchTerms = $this->params()->fromPost('searchTerms', null);
        $session = new Container('event');
        $page = $this->params()->fromRoute('page', null);
        if (! $page) {
            if (! $session->myEventsPage) {
                $page = 1;
            } 
            else {
                $page = $session->myEventsPage;
            }
        }
        $session->myEventsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (! $sort) {
            if (! $session->myEventsSort) {
                $sort = 'start_date';
            } 
            else {
                $sort = $session->myEventsSort;
            }
        }
        $session->myEventsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (! $order) {
            if (! $session->myEventsOrder) {
                $order = 'asc';
            } 
            else {
                $order = $session->myEventsOrder;
            }
        }
        $session->myEventsOrder = $order;
        $limit = $this->params()->fromRoute('results', null);
        if (! $limit) {
            if (! $session->myEventsResults) {
                $limit = 5;
            } 
            else {
                $limit = $session->myEventsResults;
            }
        }
        $session->myEventsResults = $limit;

        $eventsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countEvents($user);
        $eventsCount = $eventsCount["total"];
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedEvents = $this->entityManager->getRepository('DGIModule\Entity\Event')->getMyEvents($user, $offset, $limit, $sort, $order, $month, $year, $searchTerms, $drafts);
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/event/my-events.mobile.phtml');
        }

        $viewModel->setVariables([
            'pagedEvents' => $pagedEvents,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'eventsCount' => $eventsCount,
            'month' => $month,
            'year' => $year,
            'searchTerms' => $searchTerms,
            'drafts' => $drafts
        ]);
        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function allEventsAction()
    {
        $user = $this->identity();

        $session = new Container('level');
        $city = $session->city;

        if (!$user && !$city) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        if (!$user) {
            $user = new User();
            $user->setUsrId(0);
        }
        else {
            $user = clone($user);
        }

        if ($city) {
            $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $city]);

            if ($city && $city!==$user->getCity()) {
                $user->setUsrId(0);
                $user->setCountry($city->getCountry());
                $user->setCity($city);
            }
        }
        
        $month = $this->params()->fromRoute('month', null);
        $year = $this->params()->fromRoute('year', null);
        $searchTerms = $this->params()->fromPost('searchTerms', null);
        $session = new Container('event');
        $page = $this->params()->fromRoute('page', null);
        if (! $page) {
            if (! $session->allEventsPage) {
                $page = 1;
            }
            else {
                $page = $session->allEventsPage;
            }
        }
        $session->allEventsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (! $sort) {
            if (! $session->allEventsSort) {
                $sort = 'start_date';
            }
            else {
                $sort = $session->allEventsSort;
            }
        }
        $session->allEventsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (! $order) {
            if (! $session->allEventsOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->allEventsOrder;
            }
        }
        $session->allEventsOrder = $order;
        $limit = $this->params()->fromRoute('results', null);
        if (! $limit) {
            if (! $session->allEventsResults) {
                $limit = 10;
            }
            else {
                $limit = $session->allEventsResults;
            }
        }
        $session->allEventsResults = $limit;
        $eventsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countAllEvents($user);
        $eventsCount = $eventsCount["total"];
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedEvents = $this->entityManager->getRepository('DGIModule\Entity\Event')->getAllEvents($user, $offset, $limit, $sort, $order, $month, $year, $searchTerms);
        $viewModel = new ViewModel();
        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($terminal);

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/event/all-events.mobile.phtml');
        }

        $viewModel->setVariables([
            'pagedEvents' => $pagedEvents,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'eventsCount' => $eventsCount,
            'month' => $month,
            'year' => $year,
            'searchTerms' => $searchTerms,
        ]);
        return $viewModel;
    }
    
    
    public function upcomingEventsAction()
    {
        $user = $this->identity();

        $session = new Container('level');
        $city = $session->city;

        if (!$user && !$city) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        if (!$user) {
            $user = new User();
            $user->setUsrId(0);
        }
        else {
            $user = clone($user);
        }

        if ($city) {
            $city = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $city]);

            if ($city) {
                $user->setUsrId(0);
                $user->setCountry($city->getCountry());
                $user->setCity($city);
            }
        }

        $events = $this->entityManager->getRepository('DGIModule\Entity\Event')->getUpcomingEvents($user);
        $viewModel = new ViewModel();
        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($terminal);

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/event/upcoming-events.mobile.phtml');
        }

        $viewModel->setVariables([
            'events' => $events,
            'user' => $user
        ]);
        return $viewModel;
    }

    public function publishEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        if (! $event || $event->getUsr() != $user)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $request = $this->getRequest();
        if ($request->isPost()) {
            // if already published
            if ($event->getEventPublishedDate()) {
                return new JsonModel(['success' => true]);
            }
            $event->setEventPublishedDate(new \DateTime());
            $this->entityManager->merge($event);
            $this->entityManager->flush();
            return new JsonModel(['success' => true]);
        }
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setVariables([
            'event' => $event
        ]);
        return $viewModel;
    }

    public function cancelEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $eventUUID]);

        // event doesn't exists or the logged user is not the owner of the event
        if (! $event || $event->getUsr() != $user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            // if already canceled
            if ($event->getEventCanceledDate()) {
                return new JsonModel(['success' => true]);
            }
            $event->setEventCanceledDate(new \DateTime());
            $this->entityManager->merge($event);
            $this->entityManager->flush();
            return new JsonModel(['success' => true]);
        }
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setVariables([
            'event' => $event
        ]);
        return $viewModel;
    }

    public function viewEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);

        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $eventUUID]);

        if (! $event || ($event->getUsr()!=$user && !$event->getEventPublishedDate())) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
         
        $levelSession = new Container('level');
        if ($user && ($event->getCity()==$user->getCity() && $event->getEventLevel()==$this->config['demodyne']['level']['city'] ||
                $event->getCity()->getRegion()==$user->getCity()->getRegion() && $event->getEventLevel()==$this->config['demodyne']['level']['region'] ||
                $event->getCity()->getCountry()==$user->getCountry() && $event->getEventLevel()==$this->config['demodyne']['level']['country'])) {
            $levelSession->city = $user->getCity()->getCityId();
        }
        else {
            $levelSession->city = $event->getCity()->getCityId();
        }
        $levelSession->levelValue = $event->getEventLevel();
        $levelSession->level = array_search($levelSession->levelValue, $this->config['demodyne']['level']);

        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $attendees = $this->forward()->dispatch('DGIModule\Controller\Event', array(
            'action' => 'view-attendees',
            'id' => $eventUUID
        ));
        $viewModel->addChild($attendees, 'attendees');

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/event/view-event.mobile.phtml');
        }

        $viewModel->setVariables([
            'event' => $event,
            'user' => $user
        ]);
        return $viewModel;
    }

    public function viewAttendeesAction()
    {
        $user = $this->identity();
        $ajax = $this->params()->fromRoute('ajax');
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $eventUUID]);
        if (! $event || (!$event->getEventPublishedDate() && (!$event->hasInvitation($user) && $event->getUsr()!=$user))) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
        $page = $this->params()->fromRoute('page', 1);
        $sort = $this->params()->fromRoute('sort', 'name');
        $order = $this->params()->fromRoute('order', 'asc');
        $limit = $this->params()->fromRoute('results', 5);
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;

        $attendees = $this->entityManager->getRepository('DGIModule\Entity\User')->getEventAttendees($event, $offset, $limit, $sort, $order);
        $viewModel = new ViewModel();
        $terminal = $this->getRequest()->isXmlHttpRequest();
        if ($ajax) $viewModel->setTerminal($terminal);

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/event/view-attendees.mobile.phtml');
        }

        $viewModel->setVariables([
            'pagedUsers' => $attendees,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'event' => $event,
            'terminal' => $terminal
        ]);
        return $viewModel;
    }
    public function attendEventAction()
    {
        $response = $this->getResponse();
        $user = $this->identity();
        $eventUUID = $this->params('id');
        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        // event doesn't exists
        if (! $event || (!$event->getEventPublishedDate() && !$event->hasInvitation($user))) {
            $response->setContent(Json::encode(['success' => 0]));
            return $response;
        }
        if ($event->hasAttendee($user)) {
            $event->removeAttendee($user);
            $success = 1;
        } 
        else {
            $event->addAttendee($user);
            $success = 2;
        }
        $this->entityManager->merge($event);
        $this->entityManager->flush();
        $response->setContent(Json::encode(['success' => $success]));
        return $response;
    }
    
    public function viewInvitationsAction()
    {
        $user = $this->identity();
        $ajax = $this->params()->fromRoute('ajax');
        $eventUUID = $this->params()->fromRoute('id', null);
        if (! $eventUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $eventUUID]);
        if (! $event || $event->getUsr()!=$user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $page = $this->params()->fromRoute('page', 1);
        $sort = $this->params()->fromRoute('sort', 'name');
        $order = $this->params()->fromRoute('order', 'asc');
        $limit = $this->params()->fromRoute('results', 5);
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;

        $invitations = $this->entityManager->getRepository('DGIModule\Entity\User')->getEventInvitationsAndAttendees($event, $offset, $limit, $sort, $order);
        
        $viewModel = new ViewModel();
        if ($ajax) $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/event/view-invitations.mobile.phtml');
        }

        $viewModel->setVariables([
            'pagedUsers' => $invitations,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'event' => $event,
        ]);
        return $viewModel;
    }
    
    private static function cmp($a, $b)
    {
        if (strtolower($a->getUsrName()) == strtolower($b->getUsrName())) {
            return 0;
        }
        return (strtolower($a->getUsrName()) < strtolower($b->getUsrName())) ? -1 : 1;
    }
    
    public function inviteAttendeesAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id', null);
        if (! $eventUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
        $event = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $eventUUID]);
        // proposition doesn't exists
        if (! $event || $event->getUsr() != $user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $uuids = $this->params()->fromPost('contact', []);
            
            if ($event->isSession()) {
                $mesTitle = sprintf($this->translator->translate('Session: %s', 'DGIModule'),$event->getEventName()); 
            }
            else {
                $mesTitle = sprintf($this->translator->translate('Event: %s', 'DGIModule'),$event->getEventName());
            }
            
            foreach ($uuids as $uuid) {
                $contact = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID' => $uuid]);
                $event->addInvitation($contact);
                
                if ($event->isSession()) {
                    $mesText= sprintf($this->translator->translate('Dear %s, You have been invited by %s to a group session: %s', 'DGIModule'),
                                             $contact->getUsrName(),
                                            $user->getUsrName(),
                                            $event->getEventName());
                }
                else {
                    $mesText = sprintf($this->translator->translate('Dear %s, You have been invited by %s to an event: %s', 'DGIModule'),
                                             $contact->getUsrName(),
                                            $user->getUsrName(),
                                            $event->getEventName());
                }

                $message = new Inbox();
                $message->setToUsr($contact)
                        ->setFromUsr($user)
                        ->setIbxTitle($mesTitle)
                        ->setIbxText($mesText)
                        ->setIbxType($this->config['demodyne']['inbox']['type']['invitation'])
                        ->setEvent($event);
                $this->entityManager->persist($message);
                
            }

            $this->entityManager->merge($event);
            $this->entityManager->flush();
            return new JsonModel(['success' => true]);
        
        }
        
        $contacts = $user->getContacts()->toArray();
        usort($contacts, array('\DGIModule\Controller\EventController','cmp'));
        
        $allLevel = [];
        
        if ($event->getEventLevel()!=$this->config['demodyne']['level']['country']) {
            $allLevel=$this->entityManager->getRepository('DGIModule\Entity\User')->getUsersLevel($user, $event->getEventLevel(), $this->config['demodyne']['level']); 
        }
        
        $invitations = $this->entityManager->getRepository('DGIModule\Entity\User')->getEventInvitations($event);

        $viewModel = new ViewModel();
        
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'contacts' => $contacts,
            'allLevel' => $allLevel,
            'invitations' => $invitations,
            'user' => $user,
            'event' => $event,
        ]);
        return $viewModel;
    }
}