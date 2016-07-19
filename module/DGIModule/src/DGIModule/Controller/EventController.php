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
use DGIModule\Entity\Event;
use DGIModule\Form\AddEditEventForm;
use DGIModule\Entity\User;

class EventController extends AbstractActionController
{
    public function addEventAction()
    {
        $user = $this->identity();
        $publish = $this->params()->fromRoute('publish', false);
        
        $session = new Container('level');
        $level = $session->level;
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = new Event();

        $form = new AddEditEventForm();
        $form->setAttribute('action', $this->url()->fromRoute('city/event', array('action'=>'add-event')));
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
                    $filterR = new \Zend\Filter\File\Rename(array(
                        "target" => $target,
                        "randomize" => true
                    ));
                    $filename = $filterR->filter($files['eventImage']);
                    chmod($filename["tmp_name"], 0644);
                    $event->setEventImage(str_replace(getcwd() . "/public", '', $filename["tmp_name"]));
                } else {
                    $event->setEventImage(null);
                }
                $event->setUsr($user);
                
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
                $entityManager->persist($event);
                $entityManager->flush();
                return new \Zend\View\Model\JsonModel(array(
                    'success' => true
                ));
            }
        }
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
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
        $publish = $this->params()->fromRoute('publish', false);
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        // event doesn't exists or the logged user is not the owner of the event
        if (! $event || $event->getUsr() != $user)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $form = new AddEditEventForm();
        $form->setAttribute('action', $this->url()->fromRoute('city/event', array('action'=>'edit-event', 'id'=>$eventUUID)));
        $oldFilename = $event->getEventImage();
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
                    $filterR = new \Zend\Filter\File\Rename(array(
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
                $entityManager->merge($event);
                $entityManager->flush();
                return new \Zend\View\Model\JsonModel(array(
                    'success' => true
                ));
            }
        }
        else {
            $form->get('eventName')->setValue($event->getEventName());
            $form->get('eventDescription')->setValue($event->getEventDescription());
            $form->get('eventLink')->setValue($event->getEventLink());
            $form->get('eventStartDate')->setValue($event->getEventStartDate()
                ->format('d/m/Y H:i'));
            $form->get('eventEndDate')->setValue($event->getEventEndDate()
                ->format('d/m/Y H:i'));
            $form->get('eventLocation')->setValue($event->getEventLocation());
        }
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
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
        $publish = $this->params()->fromRoute('publish', false);
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        if (! $event || $event->getUsr() != $user)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $form = new AddEditEventForm();
        $form->setAttribute('action', $this->url()->fromRoute('city/event', array('action'=>'add-event')));
        $oldFilename = $event->getEventImage();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->bind($event);
            $form->setData($post);
            $picture = $post["eventImage"]["name"];
            if ($form->isValid()) {
                if ($picture != "") {
                    $files = $request->getFiles();
                    $target = getcwd() . "/public/img/events/event.jpg";
                    $filterR = new \Zend\Filter\File\Rename(array(
                        "target" => $target,
                        "randomize" => true
                    ));
                    $filename = $filterR->filter($files['eventImage']);
                    chmod($filename["tmp_name"], 0644);
                    $event->setEventImage(str_replace(getcwd() . "/public", '', $filename["tmp_name"]));
                } else {
                    $event->setEventImage(null);
                }
                if ($publish) {
                    $event->setEventPublishedDate(new \DateTime());
                }
                $entityManager->merge($event);
                $entityManager->flush();
                return new \Zend\View\Model\JsonModel(array(
                    'success' => true
                ));
            }
        }
        else {
            $form->get('eventName')->setValue($event->getEventName());
            $form->get('eventDescription')->setValue($event->getEventDescription());
            $form->get('eventLink')->setValue($event->getEventLink());
            $form->get('eventStartDate')->setValue($event->getEventStartDate()
                ->format('d/m/Y H:i'));
            $form->get('eventEndDate')->setValue($event->getEventEndDate()
                ->format('d/m/Y H:i'));
            $form->get('eventLocation')->setValue($event->getEventLocation());
        }
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/event/add-event.phtml');
        $viewModel->setVariables([
            'form' => $form,
            'user' => $user,
        ]);
        return $viewModel;
    }
    public function deleteEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (!$eventUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog'=>true));
        }
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array('eventUUID' => $eventUUID));
        // event not exists or user not owner
        if (!$event || $event->getUsr()!=$user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog'=>true));
        }
        $request = $this->getRequest();
        if ($request->isPost()){
            // if already deleted
            $oldFilename = $event->geteventImage();
            if ($oldFilename && file_exists(getcwd() . '/public' . $oldFilename)) {
                unlink(getcwd() . '/public' . $oldFilename);
            }
            $entityManager->remove($event);
            $entityManager->flush();
            return new \Zend\View\Model\JsonModel(array('success' => true));
        }
        $viewmodel = new ViewModel();
        //disable layout if request by Ajax
        $viewmodel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewmodel->setVariables([
            'event' => $event,
        ]);
        return $viewmodel;
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
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $eventsCount = $entityManager->getRepository('DGIModule\Entity\Event')->countEvents($user);
        $eventsCount = $eventsCount["total"];
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedEvents = $entityManager->getRepository('DGIModule\Entity\Event')->getMyEvents($user, $offset, $limit, $sort, $order, $month, $year, $searchTerms, $drafts);
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
            'month' => $month,
            'year' => $year,
            'searchTerms' => $searchTerms,
            'drafts' => $drafts
        ]);
        return $viewModel;
    }

    public function allEventsAction()
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
                $limit = 5;
            }
            else {
                $limit = $session->allEventsResults;
            }
        }
        $session->allEventsResults = $limit;
        $eventsCount = $entityManager->getRepository('DGIModule\Entity\Event')->countAllEvents($user);
        $eventsCount = $eventsCount["total"];
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $pagedEvents = $entityManager->getRepository('DGIModule\Entity\Event')->getAllEvents($user, $offset, $limit, $sort, $order, $month, $year, $searchTerms);
        $viewModel = new ViewModel();
        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($terminal);
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
        
        $guestSession = new Container('guest');
        if (!$user &&  !$guestSession->country) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        if (!$user) {
            $user = new User();
            $user->setCountry($entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $guestSession->country]));
            if ($guestSession->city) {
                $user->setCity($entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $guestSession->city]));
            }
        }
        
        $events = $entityManager->getRepository('DGIModule\Entity\Event')->getUpcomingEvents($user);
        $viewModel = new ViewModel();
        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($terminal);
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
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        if (! $event || $event->getUsr() != $user)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $request = $this->getRequest();
        if ($request->isPost()) {
            // if already published
            if ($event->getEventPublishedDate())
                return new \Zend\View\Model\JsonModel(array(
                    'success' => true
                ));
            $event->setEventPublishedDate(new \DateTime());
            $entityManager->merge($event);
            $entityManager->flush();
            return new \Zend\View\Model\JsonModel(array(
                'success' => true
            ));
        }
        $viewmodel = new ViewModel();
        // disable layout if request by Ajax
        $viewmodel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewmodel->setVariables([
            'event' => $event
        ]);
        return $viewmodel;
    }
    public function cancelEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        // event doesn't exists or the logged user is not the owner of the event
        if (! $event || $event->getUsr() != $user)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $request = $this->getRequest();
        if ($request->isPost()) {
            // if already canceled
            if ($event->getEventCanceledDate())
                return new \Zend\View\Model\JsonModel(array(
                    'success' => true
                ));
            $event->setEventCanceledDate(new \DateTime());
            $entityManager->merge($event);
            $entityManager->flush();
            return new \Zend\View\Model\JsonModel(array(
                'success' => true
            ));
        }
        $viewmodel = new ViewModel();
        // disable layout if request by Ajax
        $viewmodel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewmodel->setVariables([
            'event' => $event
        ]);
        return $viewmodel;
    }
    public function viewEventAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        if (! $event || ($event->getUsr()!=$user && !$event->getEventPublishedDate())) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        }
         
        $guestSession = new Container('guest');
        if (!$user && !$guestSession->country) {
        
            $guestSession->country = $event->getCity()->getCountry()->getCountryId();
            $guestSession->countryName = $event->getCity()->getCountry()->getCountryName();
            $levelSession = new Container('level');
            $config = $this->getServiceLocator()->get('Config');
            if ($event->getEventLevel()==$config['demodyne']['level']['country']) {
                $levelSession->level = 'country';
                $levelSession->levelValue = $config['demodyne']['level']['country'];
                $guestSession->level = 'country';
            }
            else {
                $guestSession->city = $event->getCity()->getCityId();
                $guestSession->cityName = $event->getCity()->getCityName();
                $guestSession->postalCode = $event->getCity()->getCityPostalcode();
        
                // TODO : change level to city
        
                $guestSession->level = 'country';
                $levelSession->level = 'country';
                $levelSession->levelValue = $config['demodyne']['level']['country'];
            }
        }
        $viewmodel = new ViewModel();
        // disable layout if request by Ajax
        $viewmodel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $attendees = $this->forward()->dispatch('DGIModule\Controller\Event', array(
            'action' => 'view-attendees',
            'id' => $eventUUID
        ));
        $viewmodel->addChild($attendees, 'attendees');
        $viewmodel->setVariables([
            'event' => $event,
            'user' => $user
        ]);
        return $viewmodel;
    }
    public function viewAttendeesAction()
    {
        $user = $this->identity();
        $eventUUID = $this->params('id');
        // no parameter
        if (! $eventUUID)
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        if (! $event || ($event->getUsr()!=$user && ! $event->getEventPublishedDate()))
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        $page = $this->params()->fromRoute('page', 1);
        $sort = $this->params()->fromRoute('sort', 'name');
        $order = $this->params()->fromRoute('order', 'asc');
        $limit = $this->params()->fromRoute('results', 5);
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $attendees = $entityManager->getRepository('DGIModule\Entity\User')->getEventAttendees($event, $offset, $limit, $sort, $order);
        $viewModel = new ViewModel();
        foreach ($attendees as $index => $attendee) {
            $notes =  $this->forward()->dispatch('DGIModule\Controller\UserProfile', array('action'=>'get-scores', 'id' => $attendee->getUsrUUID(), 'list' => true));
            $viewModel->addChild($notes, 'notes-'.$index);
        }
        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($terminal);
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
        $request = $this->getRequest();
        $response = $this->getResponse();
        $user = $this->identity();
        $eventUUID = $this->params('id');
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $event = $entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $eventUUID
        ));
        // event doesn't exists
        if (! $event || ! $event->getEventPublishedDate() || $event->getEventCanceledDate()) {
            $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0
            )));
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
        $entityManager->merge($event);
        $entityManager->flush();
        $response->setContent(\Zend\Json\Json::encode(array('success' => $success)));
        return $response;
    }
}