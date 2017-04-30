<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use DGIModule\Entity\Chat;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use DGIModule\Entity\Event;
use DGIModule\Form\SessionAddEditForm;
use DGIModule\Entity\Idea;

use Ramsey\Uuid\Uuid;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class SessionController extends AbstractActionController
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
    
    public function addEditSessionAction()
    {
        $user = $this->identity();
        
        // see if edit session
        $sessionUUID = $this->params()->fromRoute('id', null);

        $publish = $this->params()->fromRoute('publish', false);
        
        $session = new Container('level');

        $viewModel = new ViewModel();

        $request = $this->getRequest();

        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/session/add-edit-session.phtml');

        if ($sessionUUID) {
            
            $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);
            if (! $session || $session->getUsr()!=$user) {
                return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                    'action' => 'access-denied'
                ));
            }
            $level = $this->params()->fromPost('level', array_search($session->getEventLevel(), $this->config['demodyne']['level']));
            $form = new SessionAddEditForm($this->entityManager, $this->translator, $session->getCity());
            $form->setAttribute('action', $this->url()->fromRoute('session', ['action'=>'add-edit-session', 'id'=>$sessionUUID]));
            if (!$request->isPost()) {
                $form->get('eventName')->setValue($session->getEventName());
                $form->get('eventLink')->setValue($session->getEventLink());
                $form->get('eventDate')->setValue($session->getEventStartDate()->setTimezone(new \DateTimeZone($session->getCity()->getRegion()->getRegionTimezone()))->format('Y-m-d'));
                $form->get('eventTime')->setValue($session->getEventStartDate()->setTimezone(new \DateTimeZone($session->getCity()->getRegion()->getRegionTimezone()))->format('H:i'));
                $form->get('eventDuration')->setValue($session->getEventStartDate()->diff($session->getEventEndDate())->format('%H:%I'));
                $form->get('eventLocation')->setValue($session->getEventLocation());
            }

            $viewModel->setVariables(['session' => $session]);
        }
        else {
            $level = $this->params()->fromPost('level', $session->level);
            $session = null;
            $form = new SessionAddEditForm($this->entityManager, $this->translator, $user->getCity());
            $form->setAttribute('action', $this->url()->fromRoute('session', array('action'=>'add-edit-session')));
        }
        
        $sessionDescription = $this->params()->fromPost('eventDescription');
        if ($request->isPost()) {
            
            $session = $session?$session:new Event();
            $form->bind($session);
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $startDate = $this->params()->fromPost('eventDate').' '.$this->params()->fromPost('eventTime');
                $sessionStartDate = new \DateTime($startDate);
                $sessionEndDate = new \DateTime($startDate);
                $duration = explode(':',$this->params()->fromPost('eventDuration'));
                $sessionEndDate = $sessionEndDate->add(new \DateInterval('PT'.$duration[0].'H'.$duration[1].'M'));

                if ($sessionEndDate<new \DateTime()) {
                    $form->get('eventDuration')->setMessages([$this->translator->translate('The end date is set before current time. Please review the starting date and duration.', 'DGIModule')]);
                    $viewModel->setVariables([
                        'form' => $form,
                        'user' => $user,
                        'eventDescription' => $sessionDescription,
                        'level' => $level,
                    ]);
                    return $viewModel;
                }
                if ($publish) {
                    $session->setEventPublishedDate(new \DateTime());
                }
                
                if (!$sessionUUID) {
                    $session->setUsr($user)
                            ->setEventCreatedDate(new \DateTime())
                            ->setEventUUID(Uuid::uuid4())
                            ->setEventSession(1)
                            ->setEventLevel($this->config['demodyne']['level'][$level])
                            ->setEventImage('/img/demodyne-session.jpg')
                            ->setEventStartDate($sessionStartDate)
                            ->setEventEndDate($sessionEndDate)
                            ->setChat(new Chat('session', null, $user, "Session: '".$session->getEventName()."'"));
                            ;
                    $this->entityManager->persist($session);
                }
                else {
                    $session->setEventLevel($this->config['demodyne']['level'][$level])
                            ->setEventStartDate($sessionStartDate)
                            ->setEventEndDate($sessionEndDate)
                            ;
                    $this->entityManager->merge($session);
                }
                
                $this->entityManager->flush();
                
                if (!$sessionUUID) {
                    $this->entityManager->refresh($session);

                    /** @var \DGIModule\Entity\Chat $chat */
                    $chat = $session->getChat();
                    $chat->setChatEntityUUID($session->getEventUUID())
                         ->setChatUUID(Uuid::uuid4())
                    ;
                    $this->entityManager->merge($chat);
                    $this->entityManager->flush();

                    if ($session->getEventPublishedDate()) {
                            $this->forward()->dispatch('DGIModule\Controller\News', array('action' => 'create-news', 'id' => $session->getEventId(), 'type' => 'new_public_session'));
                    }

                    return new JsonModel(array(
                        'success' => true,
                        'url' => $publish ? $this->url()->fromRoute('session', array('action' => 'public-session-created', 'id' => $session->getEventUUID()))
                            : $this->url()->fromRoute('event', array('action' => 'invite-attendees', 'id' => $session->getEventUUID())),
                        'title' => $publish ? $this->translator->translate('Public session created', 'DGIModule') : $this->translator->translate('Invite attendees', 'DGIModule')
                    ));
                }
                
                return new JsonModel(array(
                    'success' => true
                ));
            }
        }

        $viewModel->setVariables([
            'form' => $form,
            'user' => $user,
            'eventDescription' => $sessionDescription,
            'level' => $level,
        ]);
        return $viewModel;
    }

    public function publicSessionCreatedAction()
    {
        $user = $this->identity();

        $sessionUUID = $this->params()->fromRoute('id', null);

        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);
        if (! $session || $session->getUsr()!=$user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied'
            ));
        }

        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setVariables([
            'session' => $session,
            'user' => $user
        ]);
        $viewModel->setTemplate('dgi-module/session/public-session-created.phtml');
        return $viewModel;
    }
    
    public function mySessionsAction()
    {
        $user = $this->identity();
        $searchTerms = $this->params()->fromPost('searchTerms', null);
        
        $viewModel = new ViewModel();
    
        $sessionsSection = $this->forward()->dispatch('DGIModule\Controller\Session', array('action' => 'my-sessions-list', 'searchTerms' => $searchTerms, 'ajax' => false));
        $viewModel->addChild($sessionsSection, 'sessionsSection');
        

        if ($user) {
            $countNotCompletedSessions = $this->entityManager->getRepository('DGIModule\Entity\Event')->countNotCompletedSessions($user);
            $countNotCompletedSessions = $countNotCompletedSessions["total"];
            
            $countCompletedSessions = $this->entityManager->getRepository('DGIModule\Entity\Event')->countCompletedSessions($user);
            $countCompletedSessions = $countCompletedSessions["total"];
        }
        else {
            $countNotCompletedSessions = 0;
            $countCompletedSessions = 0;
        }
        
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/session/my-sessions.mobile.phtml');
        }

        $viewModel->setVariables([
            'countNotCompletedSessions' => $countNotCompletedSessions,
            'countCompletedSessions' => $countCompletedSessions,
            'searchTerms' => $searchTerms,
            'user' => $user
        ]);
    
        return $viewModel;
    }

    public function mySessionsListAction()
    {
        $user = $this->identity();
        $searchTerms = $this->params()->fromPost('searchTerms', null);
        $ajax = $this->params()->fromRoute('ajax', true);
        $session = new Container('session');
        
        $page = $this->params()->fromRoute('page', null);
        if (! $page) {
            if (! $session->mySessionsPage) {
                $page = 1;
            }
            else {
                $page = $session->mySessionsPage;
            }
        }
        $session->mySessionsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (! $sort) {
            if (! $session->mySessionsSort) {
                $sort = 'start_date';
            }
            else {
                $sort = $session->mySessionsSort;
            }
        }
        $session->mySessionsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (! $order) {
            if (! $session->mySessionsOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->mySessionsOrder;
            }
        }
        $session->mySessionsOrder = $order;
        $limit = $this->params()->fromRoute('results', null);
        if (! $limit) {
            if (! $session->mySessionsResults) {
                $limit = 10;
            }
            else {
                $limit = $session->mySessionsResults;
            }
        }
        $session->mySessionsResults = $limit;
        
        $filter = $this->params()->fromRoute('filter', null);
        if (! $filter) {
            if (! $session->mySessionsFilter) {
                $filter = 'none';
            }
            else {
                $filter = $session->mySessionsFilter;
            }
        }
        $session->mySessionsFilter = $filter;
        

        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;


        if ($user) {
            $pagedEvents = $this->entityManager->getRepository('DGIModule\Entity\Event')->getMySessions($user, $offset, $limit, $sort, $order, $filter, false, $searchTerms, $this->config['demodyne']['level']);
        }
        else {
            $pagedEvents = [];
        }

        $viewModel = new ViewModel();
        if ($ajax) {
            $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        }

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/session/my-sessions-list.mobile.phtml');
        }

        $viewModel->setVariables([
            'pagedEvents' => $pagedEvents,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'order' => $order,
            'user' => $user,
            'filter'=>$filter,
            'searchTerms' => $searchTerms,
        ]);
        return $viewModel;
    }

    public function liveAction()
    {
        $user = $this->identity();
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID || !$user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
        
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);
        if (! $session || !$session->getEventSession() ||(!$session->getEventPublishedDate() && !$session->hasInvitation($user) && $session->getUsr()!=$user)) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
        
        // go to ended session
        if ($session->getEventSessionCompleted()) {
            return $this->forward()->dispatch('DGIModule\Controller\Session', ['action' => 'view-ended-session', 'id'=> $sessionUUID]);
        }
        
        $now = new \DateTime();
        if ($user==$session->getUsr() && $session->getEventStartDate()>$now) {
            $endTime = new \DateTime();
            $endTime->add($session->getEventStartDate()->diff($session->getEventEndDate()));
            $session->setEventStartDate($now)
                    ->setEventEndDate($endTime);
            $this->entityManager->merge($session);
            $this->entityManager->flush();
        }
        

        // go to view session if not started yet
        if ($now<$session->getEventStartDate() && $user!=$session->getUsr() ) {
            return $this->forward()->dispatch('DGIModule\Controller\Session', ['action' => 'view-session', 'id' => $sessionUUID]);
        }

        $viewModel = new ViewModel();
        
        $ideas = $this->entityManager->getRepository('DGIModule\Entity\Idea')->getIdeaList($session);
        
        $chat = $this->forward()->dispatch('DGIModule\Controller\Chat', ['action' => 'view-chat', 'id' => $session->getChat()->getChatUUID()]);
        $viewModel->addChild($chat, 'chat');

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/session/live.mobile.phtml');
        }

        $viewModel->setTerminal(true);
        $viewModel->setVariables([
            'event' => $session,
            'user' => $user,
            'ideas' => $ideas
        ]);
        return $viewModel;
    }
    
    public function viewEndedSessionAction()
    {
        $user = $this->identity();
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        /** @var \DGIModule\Entity\Event $session */
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);
        if (! $session || !$session->isSession() || (!$session->getEventPublishedDate() && !$session->hasInvitation($user) && $session->getUsr()!=$user)) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
    
        // go to view session if start time > 30 min
        $now = new \DateTime();
        if ($now<$session->getEventEndDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Session', ['action' => 'view-session', 'id' => $sessionUUID]);
        }

        $levelSession = new Container('level');
        if ($user &&
            ($session->getCity()==$user->getCity() ||
            $session->getCity()->getRegion()==$user->getCity()->getRegion() ||
            $session->getCity()->getCountry()==$user->getCountry())) {
            $levelSession->city = $user->getCity()->getCityId();
        }
        else {
            $levelSession->city = $session->getCity()->getCityId();
        }
        $levelSession->levelValue = $session->getEventLevel();
        $levelSession->level = array_search($levelSession->levelValue, $this->config['demodyne']['level']);
         
        $viewModel = new ViewModel();
    
        $ideas = $this->entityManager->getRepository('DGIModule\Entity\Idea')->getIdeaList($session);
    
        $chat = $this->forward()->dispatch('DGIModule\Controller\Chat', ['action' => 'view-chat', 'id' => $session->getChat()->getChatUUID(), 'input'=>false]);
        $viewModel->addChild($chat, 'chat');

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/session/view-ended-session.mobile.phtml');
        }

        $viewModel->setVariables([
            'event' => $session,
            'user' => $user,
            'ideas' => $ideas
        ]);
        return $viewModel;
    }
   
    public function viewSessionAction()
    {
        $user = $this->identity();
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
        /** @var \DGIModule\Entity\Event $session */
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);
        if (! $session || !$session->getEventSession() || 
            (!$session->getEventPublishedDate() && !$session->hasInvitation($user) && $session->getUsr()!=$user)) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $now = new \DateTime();

        // go to ended session
        if ($session->getEventEndDate()<=$now) {
            return $this->forward()->dispatch('DGIModule\Controller\Session', ['action' => 'view-ended-session', 'id'=> $sessionUUID]);
        }


        if ($now>=$session->getEventStartDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Session', ['action' => 'live', 'id' => $sessionUUID]);
        }

        $levelSession = new Container('level');
        if ($user && ($session->getCity()==$user->getCity() && $session->getEventLevel()==$this->config['demodyne']['level']['city'] ||
            $session->getCity()->getRegion()==$user->getCity()->getRegion() && $session->getEventLevel()==$this->config['demodyne']['level']['region'] ||
            $session->getCity()->getCountry()==$user->getCountry() && $session->getEventLevel()==$this->config['demodyne']['level']['country'])) {
            $levelSession->city = $user->getCity()->getCityId();
        }
        else {
            $levelSession->city = $session->getCity()->getCityId();
        }
        $levelSession->levelValue = $session->getEventLevel();
        $levelSession->level = array_search($levelSession->levelValue, $this->config['demodyne']['level']);

        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());

        if ($session->getUsr()==$user) {
            $attendees = $this->forward()->dispatch('DGIModule\Controller\Event', ['action' => 'view-invitations', 'id' => $sessionUUID, 'ajax' => false]);
        }
        else {
            $attendees = $this->forward()->dispatch('DGIModule\Controller\Event', ['action' => 'view-attendees', 'id' => $sessionUUID, 'ajax' => false]);
        }
        $viewModel->addChild($attendees, 'attendees');

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/session/view-session.mobile.phtml');
        }

        $viewModel->setVariables([
            'event' => $session,
            'user' => $user
        ]);
        return $viewModel;
    }
    
    public function endSessionAction()
    {
        $user = $this->identity();
    
        $sessionUUID = $this->params('id');
        if (! $sessionUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
    
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);
    
        if (! $session || !$session->isSession() || $session->getUsr()!=$user) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $session->setEventSessionCompleted(1)
                ->setEventEndDate(new \DateTime());
            $this->entityManager->merge($session);

            $chat = $session->getChat();
            $chat->setChatEndDate(new \DateTime())
                ->setChatOpened(0);
            $this->entityManager->merge($chat);

            $ideas = $this->entityManager->getRepository('DGIModule\Entity\Idea')->getIdeaList($session);

            foreach ($ideas as $index => $idea) {

                if (!$idea->getIdeaValidated()) {
                    $this->entityManager->remove($idea);
                }

            }

            $this->entityManager->flush();

            return $this->forward()->dispatch('DGIModule\Controller\Session', ['action' => 'view-ended-session', 'id' => $sessionUUID]);
        }

        return (new ViewModel(['session' => $session, 'user' => $user]))->setTerminal($request->isXmlHttpRequest());

    }
    
    public function sessionEndedAction()
    {
        $user = $this->identity();

        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied',
                'dialog' => true
            ));
        }

        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $sessionUUID
        ));

        if (! $session ||!$session->isSession()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array(
                'action' => 'access-denied',
                'dialog' => true
            ));
        }

        $viewModel = new ViewModel();

        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());

        $viewModel->setVariables([
            'session' => $session,
            'user' => $user
        ]);

        return $viewModel;
    }

    public function prolongSessionAction()
    {
        $user = $this->identity();

        $sessionUUID = $this->params('id');
        $page = $this->params()->fromRoute('page', null);

        // no parameter
        if (! $sessionUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied', 'dialog' => true]);
        }

        /** @var \DGIModule\Entity\Event $session */
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);

        if (! $session ||!$session->isSession()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied', 'dialog' => true]);
        }


        $request = $this->getRequest();

        if ($request->isPost() && $session->getUsr() === $user) {
            $endDate = clone $session->getEventEndDate();
            $endDate = $endDate->modify('+30 minute');
            $session->setEventEndDate($endDate);
            $this->entityManager->merge($session);
            $this->entityManager->flush();
            return new JsonModel([
                'endDate' => $session->getEventEndDate()->format('U') * 1000
            ]);
        }
        else {
            $viewModel = new ViewModel();
            $viewModel->setTerminal($request->isXmlHttpRequest());
            if ($page) {
                $viewModel->setTemplate('dgi-module/session/add-more-time.phtml');
            }
            elseif ($session->getUsr() !== $user) {
                $viewModel->setTemplate('dgi-module/session/client-session-will-end.phtml');
            }
            $viewModel->setVariables([
                'session' => $session,
                'user' => $user
            ]);

            return $viewModel;
        }

    }

    
    public function addIdeaAction()
    {
        $user = $this->identity();
        
        $sessionUUID = $this->params('id');
        if (! $sessionUUID) {
            return new JsonModel(['success' => false]);
        }

        /** @var \DGIModule\Entity\Event $session */
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $sessionUUID
        ));
        
        if (! $session || !$session->isSession() || 
           (!$session->getEventPublishedDate() && $user!=$session->getUsr() && !$session->hasInvitation($user)) ||
            $session->getEventSessionCompleted()) {
            return new JsonModel(['success' => false]);
        }
        
        // check if user is blocked
        if ($user!=$session->getUsr()) {
            $query = $this->entityManager->createQuery('SELECT b FROM DGIModule\Entity\BlockedUser b
                                                   WHERE b.usr=:user AND b.entityUUID=:uuid')
                               ->setParameter('user', $user)
                               ->setParameter('uuid', $session->getChat()->getChatUUID());
            $bu = $query->getOneOrNullResult();
            if ($bu) {
               return new JsonModel(['success' => false, 'message' => 'You are blocked by the owner']);
            }
        }
        
        $request = $this->getRequest();
        if ($request->isPost()) {
    
            $idea = new Idea();

            /** @var \DGIModule\Entity\Category $category */
            $category = $this->entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(['catId' => $this->params()->fromPost('ideaCategory')]);
            
            $com = explode(':', $this->params()->fromPost('ideaTitle'), 2);
            
            $idea->setIdeaName($com[0])
                 ->setIdeaDescription(count($com)>1?$com[1]:'')
                 ->setIdeaPosition($user==$session->getUsr()?$session->getValidatedIdeasCount()+1:null)
                 ->setIdeaValidated($user==$session->getUsr())
                 ->setEvent($session)
                 ->setCat($category)
                 ->setUsr($user)
                 ->setIdeaUUID(Uuid::uuid4())
                 ->setIdeaCreatedDate(new \DateTime());
            
             $this->entityManager->persist($idea);
             $this->entityManager->flush();
             return new JsonModel(['success' => true]);
                   
        }
        return new JsonModel(['success' => false]);
    }
    
    public function updateIdeaAction()
    {
        $user = $this->identity();
    
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return new JsonModel(['success' => false]);
        }
    
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $sessionUUID
        ));
    
        if (! $session || $session->getUsr()!=$user || !$session->isSession()) {
            return new JsonModel(['success' => false]);
        }
    
        $request = $this->getRequest();
        if ($request->isPost()) {
    
            
            $ideaPost = $this->params()->fromPost('idea');
            $idea = $this->entityManager->getRepository('DGIModule\Entity\Idea')->findOneBy(['ideaUUID' => $ideaPost['id']]);
            $category = $this->entityManager->getRepository('DGIModule\Entity\Category')->findOneBy(['catId' => $ideaPost['category']['id']]);

            if ($idea->getIdeaName()!=$ideaPost['element']['name']) {
                $messages = $this->entityManager->getRepository('DGIModule\Entity\ChatMessage')->findBy(['msgEntityUUID' => $ideaPost['id']]);
                foreach ($messages as $message) {
                    $message->setMsgEntityName($ideaPost['element']['name']);
                    $this->entityManager->merge($message);
                }
            }
            
            $idea->setIdeaName($ideaPost['element']['name'])
                ->setIdeaDescription($ideaPost['element']['description'])
                ->setCat($category);

            $this->entityManager->merge($idea);
            $this->entityManager->flush();
            return new JsonModel(['success' => true]);
             
        }
        return new JsonModel(['success' => false]);
    }
    
    public function validateIdeaAction()
    {
        $user = $this->identity();
    
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return new JsonModel(['success' => false]);
        }
    
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $sessionUUID
        ));
    
        if (! $session || $session->getUsr()!=$user || !$session->isSession()) {
            return new JsonModel(['success' => false, 'error'=>'No session.']);
        }
    
        $request = $this->getRequest();
        if ($request->isPost()) {
    
            $ideaPost = $this->params()->fromPost('idea');
    
            $idea = $this->entityManager->getRepository('DGIModule\Entity\Idea')->findOneBy(['ideaUUID' => $ideaPost['id']]);
    
            $idea->setIdeaPosition($user==$session->getUsr()?$session->getValidatedIdeasCount()+1:null)
                 ->setIdeaValidated(true);
            
            $this->entityManager->merge($idea);
            $this->entityManager->flush();
            return new JsonModel(['success' => true]);
             
        }
        return new JsonModel(['success' => false, 'error'=>'No POST.']);
    }
    
    public function deleteIdeaAction()
    {
        $user = $this->identity();
    
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return new JsonModel(['success' => false]);
        }
    
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $sessionUUID
        ));
    
        if (! $session || $session->getUsr()!=$user || !$session->isSession()) {
            return new JsonModel(['success' => false]);
        }
    
        $request = $this->getRequest();
        if ($request->isPost()) {
    
    
            $ideaPost = $this->params()->fromPost('idea');
    
            $idea = $this->entityManager->getRepository('DGIModule\Entity\Idea')->findOneBy(['ideaUUID' => $ideaPost['id']]);
    
            $this->entityManager->remove($idea);
            $this->entityManager->flush();
            
            $ideas = $this->entityManager->getRepository('DGIModule\Entity\Idea')->getValidatedIdeaList($session);
            
            // resort ideas
            foreach ($ideas as $index => $idea) {
            
                $idea->setIdeaPosition($index+1);
            
                $this->entityManager->merge($idea);
            }
            $this->entityManager->flush();
            
            
            return new JsonModel(['success' => true]);
             
        }
        return new JsonModel(['success' => false]);
    }
    
    
    public function sortIdeasAction()
    {
        $user = $this->identity();
    
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return new JsonModel(['success' => false]);
        }
    
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(array(
            'eventUUID' => $sessionUUID
        ));
    
        if (! $session || $session->getUsr()!=$user || !$session->isSession()) {
            return new JsonModel(['success' => false]);
        }
    
        $request = $this->getRequest();
        if ($request->isPost()) {
            $ideas = $this->params()->fromPost('ideas');
            foreach ($ideas as $index => $ideaPost) {
                $idea = $this->entityManager->getRepository('DGIModule\Entity\Idea')->findOneBy(['ideaUUID' => $ideaPost['id']]);
                $idea->setIdeaPosition($index+1);
                $this->entityManager->merge($idea);
            }
            $this->entityManager->flush();
            return new JsonModel(['success' => true]);
        }
        return new JsonModel(['success' => false]);
    }
    
    public function ideaListAction()
    {
        $user = $this->identity();
        
        $sessionUUID = $this->params('id');
        // no parameter
        if (! $sessionUUID) {
            return new JsonModel(['ideas' => []]);
        }

        /** @var \DGIModule\Entity\Event $session */
        $session = $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID' => $sessionUUID]);
        if (! $session || !$session->isSession()) {
            return new JsonModel(['ideas' => []]);
        }
    
        $ideas = $this->entityManager->getRepository('DGIModule\Entity\Idea')->getIdeaList($session);
        $ideasJson = [];
        foreach ($ideas as $idea) {
            $ideasJson[] = [
                'position' => $idea->getIdeaPosition(),
                'category' => [
                    'image' =>  '/files/'.$idea->getCat()->getCatImage(),
                    'name' => $idea->getCat()->getCatName(),
                    'id' =>  $idea->getCat()->getCatId()
				],
			    'element' => [
				    'name' => $idea->getIdeaName(),
			        'description' => strip_tags($idea->getIdeaDescription()),
			        'validated' => $idea->getIdeaValidated(),
			        'prop' => count($idea->getProposals())
			    ],
                'user' => [
                    'name' => $idea->getUsr()->getUsrName(),
                    'uuid' => $idea->getUsr()->getUsrUUID(),
                    'picture' => $idea->getUsr()->getUsrPicture(),
                ],
			    'id' => $idea->getIdeaUUID(),
            ];
        }
        
        return new JsonModel(['ideas' => $ideasJson, 'ended' => $session->getEventSessionCompleted(), 'endDate'=>$session->getEventEndDate()->format('U') * 1000]);
        
    }
}