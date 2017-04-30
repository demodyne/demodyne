<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use DGIModule\Entity\Inbox;
use DGIModule\Form\NewMessageForm;
use DGIModule\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class InboxController extends AbstractActionController
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

    public function myInboxAction() {
        $user = $this->identity();
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $listReceivedSection = $this->forward()->dispatch('DGIModule\Controller\Inbox', array('action' => 'list-received'));
        $viewModel->addChild($listReceivedSection, 'listReceivedSection');
        $session = new Container('inbox');
        $viewReceived = false;
        if ($session->viewReceived) {
            $viewReceivedSection = $this->forward()->dispatch('DGIModule\Controller\Inbox', array('action' => 'view', 'id'=>$session->viewReceived, 'type'=>'received'));
            $viewModel->addChild($viewReceivedSection, 'viewReceivedSection');
            $viewReceived = true;
        }

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/my-inbox.mobile.phtml');
        }

        $viewModel->setVariables([
             'viewReceived' => $viewReceived,
             'user' => $user
        ]);
        return $viewModel;
    }
    public function listReceivedAction() {
        $user = $this->identity();
        $session = new Container('inbox');
        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->listReceivedPage) {
                $page = 1;
            }
            else {
                $page = $session->listReceivedPage;
            }
        }
        $session->listReceivedPage = $page;
        $filter = $this->params()->fromRoute('filter', null);
        if (!$filter) {
            if (!$session->listReceivedFilter) {
                $filter = 'none';
            }
            else {
                $filter = $session->listReceivedFilter; 
            }
        }
        $session->listReceivedFilter = $filter;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->listReceivedResults) {
                $limit = 10;
            }
            else {
                $limit = $session->listReceivedResults;
            }
        }
        $session->listReceivedResults = $limit;
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
            $pagedInbox = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->getUserPagedReceivedMessages($user, $offset, $limit, $this->config['demodyne']['inbox']['type'][$filter]);
            // get unread messages
            /// @todo Optimize in repository by SQL count
            $unreadMessages = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['toUsr'=>$user, 'ibxViewed'=>0, 'ibxToTrashDate'=>null, 'ibxToDeletedDate'=>null]);
            $inboxUnreadMessagesCount = count($unreadMessages);
        }
        else {
            $pagedInbox = [];
            $inboxUnreadMessagesCount = 0;
        }
        $viewModel = new ViewModel();
        $ajax = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($ajax);
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/list.mobile.phtml');
        }
        else {
            $viewModel->setTemplate('dgi-module/inbox/list.phtml');
        }
        $viewModel->setVariables([
            'pagedInbox' => $pagedInbox,
            'limit' => $limit,
            'page' => $page,
            'filter' => $filter,
            'user' => $user,
            'inboxUnreadMessagesCount' => $inboxUnreadMessagesCount,
            'ajax' => $ajax,
            'list' => 'received',
            'uuid' => $session->viewReceived
        ]);
        return $viewModel;
    }

    public function unreadMessagesAction() {
        $user = $this->identity();

        if ($user) {
            $unreadMessages = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['toUsr'=>$user, 'ibxViewed'=>0, 'ibxToTrashDate'=>null, 'ibxToDeletedDate'=>null]);
            $inboxUnreadMessagesCount = count($unreadMessages);
        }
        else {
            $inboxUnreadMessagesCount = 0;
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/list.mobile.phtml');
        }
        else {
            $viewModel->setTemplate('dgi-module/inbox/list.phtml');
        }
        $viewModel->setVariables([
            'inboxUnreadMessagesCount' => $inboxUnreadMessagesCount,
        ]);
        return $viewModel;
    }

    public function listSentAction() {
        $user = $this->identity();
        $session = new Container('inbox');
        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->listSentPage) {
                $page = 1;
            }
            else {
                $page = $session->listSentPage;
            }
        }
        $session->listSentPage = $page;
        $filter = $this->params()->fromRoute('filter', null);
        if (!$filter) {
            if (!$session->listSentFilter) {
                $filter = 'none';
            }
            else {
                $filter = $session->listSentFilter;
            }
        }
        $session->listSentFilter = $filter;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->listSentResults) {
                $limit = 10;
            }
            else {
                $limit = $session->listSentResults;
            }
        }
        $session->listSentResults = $limit;
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
            $pagedInbox = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->getUserPagedSentMessages($user, $offset, $limit, $this->config['demodyne']['inbox']['type'][$filter]);
        }
        else {
            $pagedInbox = [];
        }
        $toUsers = array();
        foreach ($pagedInbox as $index => $inboxMessage) {
            $groupMessages = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $inboxMessage->getIbxGroup()]);
            $toUsers[$index] = array();
            foreach ($groupMessages as $message) {
                $toUsers[$index][] = $message->getToUsr();
            }
        }
        $viewModel = new ViewModel();
        $ajax = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($ajax);
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/list.mobile.phtml');
        }
        else {
            $viewModel->setTemplate('dgi-module/inbox/list.phtml');
        }
        $viewModel->setVariables([
            'pagedInbox' => $pagedInbox,
            'toUsers' => $toUsers,
            'limit' => $limit,
            'page' => $page,
            'filter' => $filter,
            'user' => $user,
            'ajax' => $ajax,            
            'list' => 'sent',
            'uuid' => $session->viewSent
        ]);
        return $viewModel;
    }
    public function listTrashAction() {
        $user = $this->identity();
        $session = new Container('inbox');
        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->listTrashPage) {
                $page = 1;
            }
            else {
                $page = $session->listTrashPage;
            }
        }
        $session->listTrashPage = $page;
        $filter = $this->params()->fromRoute('filter', null);
        if (!$filter) {
            if (!$session->listTrashFilter) {
                $filter = 'none';
            }
            else {
                $filter = $session->listTrashFilter;
            }
        }
        $session->listTrashFilter = $filter;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->listTrashResults) {
                $limit = 10;
            }
            else {
                $limit = $session->listTrashResults;
            }
        }
        $session->listTrashResults = $limit;
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
        $pagedInbox = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->getUserPagedTrashMessages($user, $offset, $limit, $this->config['demodyne']['inbox']['type'][$filter]);
        }
        else {
            $pagedInbox = [];
        }
        $toUsers = array();
        foreach ($pagedInbox as $index => $inboxMessage) {
            $groupMessages = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $inboxMessage->getIbxGroup()]);
            $toUsers[$index] = array();
            foreach ($groupMessages as $message) {
                $toUsers[$index][] = $message->getToUsr();
            }
        }
        $viewModel = new ViewModel();
        $ajax = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($ajax);
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/list.mobile.phtml');
        }
        else {
            $viewModel->setTemplate('dgi-module/inbox/list.phtml');
        }
        $viewModel->setVariables([
            'pagedInbox' => $pagedInbox,
            'toUsers' => $toUsers,
            'limit' => $limit,
            'page' => $page,
            'filter' => $filter,
            'user' => $user,
            'ajax' => $ajax,            
            'list' => 'trash',
            'uuid' => $session->viewTrash
        ]);
        return $viewModel;
    }
    public function listSearchAction() {
        $user = $this->identity();
        $page = $this->params()->fromRoute('page', 1);
        $filter = $this->params()->fromRoute('filter', 'none');
        $limit= $this->params()->fromRoute('results', 10);
        $searchKeywords = $this->params()->fromRoute('sk', '');
        if ($searchKeywords == '') {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $searchReceiver = $this->params()->fromRoute('sr', 1);
        $searchSender = $this->params()->fromRoute('ss', 1);
        $searchSubject = $this->params()->fromRoute('st', 1);
        $searchMessage = $this->params()->fromRoute('sm', 1);
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
        $pagedInbox = $this->entityManager->getRepository('DGIModule\Entity\Inbox')
                            ->getUserPagedSearchMessages($user, $searchKeywords, $searchReceiver, $searchSender, $searchSubject, $searchMessage, 
                                $offset, $limit, $this->config['demodyne']['inbox']['type'][$filter]);
        }
        else {
            $pagedInbox = [];
        }
        $viewModel = new ViewModel();
          $ajax = $this->getRequest()->isXmlHttpRequest();
          $viewModel->setTerminal($ajax);
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/list.mobile.phtml');
        }
        else {
            $viewModel->setTemplate('dgi-module/inbox/list.phtml');
        }
          $viewModel->setVariables([
              'pagedInbox' => $pagedInbox,
              'limit' => $limit,
              'page' => $page,
              'filter' => $filter,
              'user' => $user,
              'ajax' => $ajax,
              'list' => 'search', 
              'sk' => $searchKeywords, 
              'sr' => $searchReceiver,
              'ss' => $searchSender, 
              'st' => $searchSubject,
              'sm'=> $searchMessage,
              'uuid' => '0'
          ]);
          return $viewModel;
    }
    public function myContactsAction() {
        $user = $this->identity();
        
        $city = $this->layout()->city;
        if (!$user &&  !$city) {
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
            $user->setUsrId(0);
            $user->setCountry($city->getCountry());
            $user->setCity($city);
        }

        $session = new Container('inbox');
        $page = $this->params()->fromRoute('page', null);
        if (!$page) {
            if (!$session->myContactsPage) {
                $page = 1;
            }
            else {
                $page = $session->myContactsPage;
            }
        }
        $session->myContactsPage = $page;
        $sort = $this->params()->fromRoute('sort', null);
        if (!$sort) {
            if (!$session->myContactsSort) {
                $sort = 'name';
            }
            else {
                $sort = $session->myContactsSort;
            }
        }
        $session->myContactsSort = $sort;
        $order = $this->params()->fromRoute('order', null);
        if (!$order) {
            if (!$session->myContactsOrder) {
                $order = 'asc';
            }
            else {
                $order = $session->myContactsOrder;
            }
        }
        $session->myContactsOrder = $order;
        $limit= $this->params()->fromRoute('results', null);
        if (!$limit) {
            if (!$session->myContactsResults) {
                $limit = 10;
            }
            else {
                $limit = $session->myContactsResults;
            }
        }
        $session->myContactsResults = $limit;

        $totalResults = $user?count($user->getContacts()):0;
        $page = $limit!='all'? (ceil($totalResults/$limit) < $page ? ceil($totalResults/$limit) : $page) : $page; // @todo Goto last page if page > last page
        $viewModel = new ViewModel();
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $contacts = $this->entityManager->getRepository('DGIModule\Entity\User')->getPagedContacts($user, $offset, $limit, $sort, $order);
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/my-contacts.mobile.phtml');
        }

        $viewModel->setVariables([
           'contacts' => $contacts,
           'limit' => $limit,
           'page' => $page,
           'sort' => $sort,
           'order' => $order,
           'user' => $user,
           'totalResults' =>$totalResults
       ]);
       return $viewModel;
    }
    public function addRemoveContactAction() {
        $user = $this->identity();
        $uuid = $this->params('id', '0');
        $contact = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID' => $uuid, 'usrDeletedDate'=>null]);
        if (!$contact) {
            return new JsonModel(['success' => false]);
        }
        if ($user->getContacts()->contains($contact)) {
            $user->removeContact($contact);
            $added = false;
        }
        else {
            $user->addContact($contact);
            $added = true;
        }
        $this->entityManager->merge($user);
        $this->entityManager->flush();
        return new JsonModel(['success' => true, 'added' => $added, 'contact'=>$uuid]);
    }
    public function deleteOneAction() {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');
        $type = $this->params()->fromRoute('type', 'received');

        $message = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new JsonModel(['success' => false]);
        }
        if (($type=='received' && $user != $message->getToUsr()) || 
            ($type=='sent' && $user != $message->getFromUsr()) ||
            ($type=='trash' && $user != $message->getToUsr() && $user != $message->getFromUsr())) {
            return new JsonModel(['success' => false]);
        }
        $session = new Container('inbox');
        $trash = true;
        if ($type=='received' && !$message->getIbxToTrashDate()) {
            $message->setIbxToTrashDate(new \DateTime());
            $session->viewReceived = null;
        }
        if ($type=='sent' && !$message->getIbxFromTrashDate()) {
            $message->setIbxFromTrashDate(new \DateTime());
            $session->viewSent = null;
        }
        if ($type=='trash') {
            if ($user == $message->getToUsr() && $message->getIbxToTrashDate()) {
                $message->setIbxToDeletedDate(new \DateTime());
                $session->viewTrash = null;
            }
            if ($user == $message->getFromUsr() && $message->getIbxFromTrashDate()) {
                $message->setIbxFromDeletedDate(new \DateTime());
                $session->viewTrash =null;
            }
            $trash = false;
        }
        $this->entityManager->merge($message);
        $this->entityManager->flush();
        return new JsonModel(['success' => true, 'trash' => $trash, 'type' => $type]);
    }


    /**
     * @todo Delete message permanently after 30 days
     * @return ViewModel
     */
    public function deleteSelectedAction() {
        $user = $this->identity();
        $request = $this->getRequest();
        $mails =$request->isPost()? $this->params()->fromPost('mail') :  $this->params()->fromQuery('mail');
        $type = $this->params()->fromRoute('type', 'received');
        $errors = [];
        $messages = [];
        $messageNotExists = $this->translator->translate("One or more mails do not exists.", 'DGIModule');
        $messageNotOwner = $this->translator->translate("You are not authorised to delete one or more messages.", 'DGIModule');
        // verify if messages exist and the user can delete
        foreach ($mails as $mail) {
            $message = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $mail]);
            if (!$message && !$errors.containts($messageNotExists)) {
                $errors[] = $messageNotExists;
            }
            else {
                if (($type=='received' && $user != $message->getToUsr()) ||
                    ($type=='sent' && $user != $message->getFromUsr()) ||
                    ($type=='trash' && $user != $message->getToUsr() && $user != $message->getFromUsr())) {
                        if (!$errors.containts($messageNotOwner)) {
                            $errors[] = $messageNotOwner;
                        }
                }
                $messages[] = $message;
            }
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $session = new Container('inbox');
        if ($request->isPost() && !count($errors)){
            foreach ($messages as $message) {
                if ($type=='received' && !$message->getIbxToTrashDate()) {
                    $message->setIbxToTrashDate(new \DateTime());
                    if ($message->getIbxUUID()==$session->viewReceived){
                        $session->viewReceived = null;
                    }
                }
                if ($type=='sent' && !$message->getIbxFromTrashDate()) {
                    $message->setIbxFromTrashDate(new \DateTime());
                    if ($message->getIbxUUID()==$session->viewSent){
                        $session->viewSent = null;
                    }
                }
                if ($type=='trash') {
                    if ($user == $message->getToUsr() && $message->getIbxToTrashDate()) {
                        $message->setIbxToDeletedDate(new \DateTime());
                    }
                    if ($user == $message->getFromUsr() && $message->getIbxFromTrashDate()) {
                        $message->setIbxFromDeletedDate(new \DateTime());
                    }
                    if ($message->getIbxUUID()==$session->viewTrash){
                        $session->viewTrash = null;
                    }
                }
                $this->entityManager->merge($message);
            }
            $this->entityManager->flush();
            $viewModel->setTemplate('dgi-module/inbox/delete-selected-success.phtml');
        }
        $viewModel->setVariables([
            'mails' => $mails,
            'errors' => $errors,
            'type' => $type
        ]);
        return $viewModel;
    }

    public function viewAction() {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');
        $type = $this->params()->fromRoute('type', 'received');
        $session = new Container('inbox');
        if ($type=='received') {
            $session->viewReceived = $uuid;
        }
        elseif ($type=='sent') {
            $session->viewSent = $uuid;
        }
        else $session->viewTrash = $uuid;

        $inboxMessage = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$inboxMessage || ($inboxMessage->getToUsr()!=$user && $inboxMessage->getFromUsr()!=$user)) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $groupMessages = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $inboxMessage->getIbxGroup()]);
        $toUsers = array();
        foreach ($groupMessages as $message) {
            $toUsers[] = $message->getToUsr();
        }
        $unreadMessage = false;
        if (!$inboxMessage->getIbxViewed() && $type=='received') {
            $inboxMessage->setIbxViewed(1);
            $this->entityManager->merge($inboxMessage);
            $this->entityManager->flush();
            $unreadMessage = true;
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/inbox/view.mobile.phtml');
        }

        $viewModel->setVariables([
            'unreadMessage' => $unreadMessage,
            'message' => $inboxMessage,
            'toUsers' => $toUsers,
            'type' => $type,
            'user' => $user
        ]);
        return $viewModel;
    }

    public function viewMessageAction() {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');
        $type = $this->params()->fromRoute('type', 'received');

        $inboxMessage = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$inboxMessage || ($inboxMessage->getToUsr()!=$user && $inboxMessage->getFromUsr()!=$user)) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $session = new Container('inbox');
        if ($type=='received') {
            $session->viewReceived = $uuid;
        }
        elseif ($type=='sent') {
            $session->viewSent = $uuid;
        }
        else $session->viewTrash = $uuid;

        return $this->redirect()->toRoute('country', [], ['fragment' => 'inbox']);
    }


    public function createMessageAction() {
        $to = $this->params('to');
        $type = $this->params('type', 0);
        $uuid = $this->params('uuid', '00000000-0000-0000-0000-000000000000');

        $toUsr = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrName' => $to]);
        $group = uniqid('', true);
        $message = new Inbox();
        $message->setToUsr($toUsr)
                ->setIbxType($type)
                ->setIbxGroup($group);
        switch ($type) {
            case $this->config['demodyne']['inbox']['type']['new_comment']:
                $comment = $this->entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID' => $uuid]);
                $message->setCom($comment)
                        ->setIbxTitle($comment->getProp()?_('Proposal: ').$comment->getProp()->getPropName():($comment->getProgram()?_('Program: ').$comment->getProgram()->getProgName():'Comment: to add comment ref'))
                        ->setIbxText($comment->getComText())
                        ->setFromUsr($comment->getUsr());
                break;
            case $this->config['demodyne']['inbox']['type']['new_step']:
                break;
            case $this->config['demodyne']['inbox']['type']['champion_news']:
                break;
        }
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return true;
    }

    public function getContactsAction() {
        $user = $this->identity();
        $contact = $this->params()->fromQuery('term');
        $contacts = $this->entityManager->getRepository('DGIModule\Entity\User')->getContacts($user, $contact);
        $contactList = array();
        foreach ($contacts as $contact) {
            $contactItem["value"] = $contact->getUsrName();
            $contactList[] = $contactItem;
        }
        return new JsonModel( $contactList );
    }

    public function newMessageAction()
    {
        $user = $this->identity();
        $to = $this->params()->fromRoute('to', '');
        $form = new NewMessageForm();
        $form->get('msgTo')->setValue($to);
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $msg = $this->params()->fromPost('msgText', '');
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){
                // prepare data
                $to = $this->params()->fromPost('msgTo');
                $title = $this->params()->fromPost('msgTitle');
                $msg = $this->params()->fromPost('msgText');
                $com = $this->params()->fromPost('comment', '');
                $toList = explode(',', preg_replace('/\s+/', '', $to));
                $messages = array();
                $toUsrList = array();
                foreach ($toList as $toValue) { // TODO: max 10 receivers by email
                    if ($toValue=='') continue;
                    $toUsr = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrName' => $toValue]); // @todo check only users from the same city? : pays
                    if (!$toUsr) {
                        $messages[] = 'No user with the name "'.$toValue.'"';
                    }
                    else { // TODO: search for dublons (do not send email more than once)
                        $toUsrList[] = $toUsr;
                    }
                }
                if (count($messages)>0) { // if errors 
                    $form->get('msgTo')->setMessages($messages);
                }
                else {
                    if ($com=='') {
                        $group = uniqid('', true);
                        foreach ($toUsrList as $toUsr) {
                            $message = new Inbox();
                            $message->setToUsr($toUsr)
                                    ->setFromUsr($user)
                                    ->setIbxTitle($title)
                                    ->setIbxText($msg)
                                    ->setIbxType($this->config['demodyne']['inbox']['type']['private_message'])
                                    ->setIbxGroup($group);
                            $this->entityManager->persist($message);
                            $this->entityManager->flush(); // must do a flush to retrieve message in email controller

                            // TODO: add receiver to contacts if not in contacts

                            if ($toUsr->getDigest()->getDigestAlertPrivate()==$this->config['demodyne']['email']['alert']['instant']) {
                                $this->forward()->dispatch('DGIModule\Controller\Email', array(
                                    'action' => 'new-private-message',
                                    'id' => $message->getIbxUUID(),
                                    'email' => 'true'
                                ));
                            }
                        }
                    }
                    else {
                        $comment = $this->entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID' => $com]);
                        $this->forward()->dispatch('DGIModule\Controller\Comment', array(
                            'action' => 'create-comment',
                            'type' => $comment->getProp()?'proposal':'program',
                            'id' => $comment->getProp()?$comment->getProp()->getPropUUID():$comment->getProg()->getProgUUID(),
                            'com' => $msg
                        ));
                    }
                    $viewModel->setTemplate('dgi-module/inbox/new-message-success.phtml');
                    return $viewModel;
                }
            }
        }
        else {
            $to =  $this->params('to');
            $form->get('msgTo')->setValue($to);
        }
        $viewModel->setVariables([
            'form'=>$form,
            'step'=>0, 
            'msg' => $msg
        ]);
        return $viewModel;
    }
    public function replyAction()
    {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');

        $message = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new JsonModel(array('success' => false));
        }
        if ($user != $message->getToUsr() || $message->getIbxToDeletedDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog' => true));
        }
        $form = new NewMessageForm();
        $form->get('msgTo')->setValue($message->getFromUsr()->getUsrName());
        $form->get('msgTitle')->setValue('Re: '.$message->getIbxTitle());
        $msg = sprintf($this->translator->translate('<br><br>On %s, <span class="badge">%s</span> wrote: <br><blockquote>%s</blockquote><br>', 'DGIModule'),
                        $message->getIbxCreatedDate()->format("d/m/Y H:i"),
                        $message->getFromUsr()->getUsrName(),
                        $message->getIbxText());
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/inbox/new-message.phtml');
        $viewModel->setVariables([
            'form'=>$form,
            'msg' => $msg
        ]);
        if ($message->getIbxType() == $this->config['demodyne']['inbox']['type']['new_comment']) {
            $viewModel->setVariable('comment', $message->getCom()->getComUUID());
            $form->get('msgTitle')->setValue($message->getIbxTitle());
        }
        return $viewModel;
    }
    public function replyAllAction()
    {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');

        $message = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new JsonModel(array('success' => false));
        }
        if ($user != $message->getToUsr() || $message->getIbxToDeletedDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog' => $this->getRequest()->isXmlHttpRequest()));
        }
        $form = new NewMessageForm();
        $groupMessages = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $message->getIbxGroup()]);
        $toUsers = '';
        foreach ($groupMessages as $msg) {
            $toUsers = $toUsers . $msg->getToUsr()->getUsrName();
            if ($msg !== end($groupMessages)) {
                $toUsers = $toUsers . ', ';
            }
        }
        $form->get('msgTo')->setValue($toUsers);
        $form->get('msgTitle')->setValue('Re: '.$message->getIbxTitle());
        $msg = sprintf($this->translator->translate('<br><br>On %s, <span class="badge">%s</span> wrote: <br><blockquote>%s</blockquote><br>', 'DGIModule'),
            $message->getIbxCreatedDate()->format("d/m/Y H:i"),
            $message->getFromUsr()->getUsrName(),
            $message->getIbxText());
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/inbox/new-message.phtml');
        $viewModel->setVariables([
            'form'=>$form,
            'msg' => $msg
        ]);
        return $viewModel;
    }

    public function forwardAction()
    {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');
        $message = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new JsonModel(array('success' => false));
        }
        if ($user != $message->getToUsr() || $message->getIbxToDeletedDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog' => true));
        }
        $form = new NewMessageForm();
        $form->get('msgTitle')->setValue('Fwd: '.$message->getIbxTitle());
        $msg = sprintf($this->translator->translate('<br><br>On %s, <span class="badge">%s</span> wrote: <br><blockquote>%s</blockquote><br>', 'DGIModule'),
            $message->getIbxCreatedDate()->format("d/m/Y H:i"),
            $message->getFromUsr()->getUsrName(),
            $message->getIbxText());
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/inbox/new-message.phtml');
        $viewModel->setVariables([
            'form'=>$form,
            'msg' => $msg
        ]);
        return $viewModel;
    }
}