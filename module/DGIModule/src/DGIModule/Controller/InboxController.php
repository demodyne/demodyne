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
use DGIModule\Entity\Inbox;
use DGIModule\Form\NewMessageForm;
use DGIModule\Entity\User;

class InboxController extends AbstractActionController
{
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
                $limit = 5;
            }
            else {
                $limit = $session->listReceivedResults;
            }
        }
        $session->listReceivedResults = $limit;
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $config = $this->getServiceLocator()->get('Config');
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
            $pagedInbox = $entityManager->getRepository('DGIModule\Entity\Inbox')->getUserPagedReceivedMessages($user, $offset, $limit, $config['demodyne']['inbox']['type'][$filter]);
            // get unread messages
            $query = $entityManager->createQuery('SELECT COUNT(distinct i.ibxId) as inboxUnreadMessagesCount
                                                  FROM DGIModule\Entity\Inbox i
                                                  WHERE i.toUsr=:user AND i.ibxViewed=0 AND i.ibxToTrashDate IS NULL AND i.ibxToDeletedDate IS NULL')
                                  ->setParameter('user', $user);
            $inboxUnreadMessagesCount = $query->getOneOrNullResult();
        }
        else {
            $pagedInbox = [];
            $inboxUnreadMessagesCount = 0;
        }
        $viewModel = new ViewModel();
        $ajax = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($ajax);
        $viewModel->setTemplate('dgi-module/inbox/list.phtml');
        $viewModel->setVariables([
            'pagedInbox' => $pagedInbox,
            'limit' => $limit,
            'page' => $page,
            'filter' => $filter,
            'user' => $user,
            'inboxUnreadMessagesCount' => $inboxUnreadMessagesCount['inboxUnreadMessagesCount'],
            'ajax' => $ajax,
            'list' => 'received',
            'uuid' => $session->viewReceived
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
                $limit = 5;
            }
            else {
                $limit = $session->listSentResults;
            }
        }
        $session->listSentResults = $limit;
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $config = $this->getServiceLocator()->get('Config');
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
            $pagedInbox = $entityManager->getRepository('DGIModule\Entity\Inbox')->getUserPagedSentMessages($user, $offset, $limit, $config['demodyne']['inbox']['type'][$filter]);
        }
        else {
            $pagedInbox = [];
        }
        $toUsers = array();
        foreach ($pagedInbox as $index => $inboxMessage) {
            $groupMessages = $entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $inboxMessage->getIbxGroup()]);
            $toUsers[$index] = array();
            foreach ($groupMessages as $message) {
                $toUsers[$index][] = $message->getToUsr();
            }
        }
        $viewModel = new ViewModel();
        $ajax = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($ajax);
        $viewModel->setTemplate('dgi-module/inbox/list.phtml');
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
                $limit = 5;
            }
            else {
                $limit = $session->listTrashResults;
            }
        }
        $session->listTrashResults = $limit;
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $config = $this->getServiceLocator()->get('Config');
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
        $pagedInbox = $entityManager->getRepository('DGIModule\Entity\Inbox')->getUserPagedTrashMessages($user, $offset, $limit, $config['demodyne']['inbox']['type'][$filter]);
        }
        else {
            $pagedInbox = [];
        }
        $toUsers = array();
        foreach ($pagedInbox as $index => $inboxMessage) {
            $groupMessages = $entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $inboxMessage->getIbxGroup()]);
            $toUsers[$index] = array();
            foreach ($groupMessages as $message) {
                $toUsers[$index][] = $message->getToUsr();
            }
        }
        $viewModel = new ViewModel();
        $ajax = $this->getRequest()->isXmlHttpRequest();
        $viewModel->setTerminal($ajax);
        $viewModel->setTemplate('dgi-module/inbox/list.phtml');
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
        $limit= $this->params()->fromRoute('results', 5);
        $searchKeywords = $this->params()->fromRoute('sk', '');
        if ($searchKeywords == '') {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $searchReceiver = $this->params()->fromRoute('sr', 1);
        $searchSender = $this->params()->fromRoute('ss', 1);
        $searchSubject = $this->params()->fromRoute('st', 1);
        $searchMessage = $this->params()->fromRoute('sm', 1);
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $config = $this->getServiceLocator()->get('Config');
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        if ($user) {
        $pagedInbox = $entityManager->getRepository('DGIModule\Entity\Inbox')
                            ->getUserPagedSearchMessages($user, $searchKeywords, $searchReceiver, $searchSender, $searchSubject, $searchMessage, 
                                $offset, $limit, $config['demodyne']['inbox']['type'][$filter]);
        }
        else {
            $pagedInbox = [];
        }
        $viewModel = new ViewModel();
          $ajax = $this->getRequest()->isXmlHttpRequest();
          //var_dump($ajax);
          $viewModel->setTerminal($ajax);
          $viewModel->setTemplate('dgi-module/inbox/list.phtml');
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
        
        $guestSession = new Container('guest');
        if (!$user &&  !$guestSession->country) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        if (!$user) {
            $user = new User();
            $user->setUsrId(0);
            $user->setCountry($entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId' => $guestSession->country]));
            if ($guestSession->city) {
                $user->setCity($entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => $guestSession->city]));
            }
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
                $limit = 5;
            }
            else {
                $limit = $session->myContactsResults;
            }
        }
        $session->myContactsResults = $limit;
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
       $totalResults = $user?count($user->getContacts()):0;
       $page = $limit!='all'? (ceil($totalResults/$limit) < $page ? ceil($totalResults/$limit) : $page) : $page; // @todo Goto last page if page > last page
       $viewModel = new ViewModel();
       $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $contacts = $entityManager->getRepository('DGIModule\Entity\User')->getPagedContacts($user, $offset, $limit, $sort, $order);
       $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
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
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $contact = $entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID' => $uuid, 'usrDeletedDate'=>null]);
        if (!$contact) {
            return new \Zend\View\Model\JsonModel(array('success' => false));
        }
        if ($user->getContacts()->contains($contact)) {
            $user->removeContact($contact);
            $added = false;
        }
        else {
            $user->addContact($contact);
            $added = true;
        }
        $entityManager->merge($user);
        $entityManager->flush();
        return new \Zend\View\Model\JsonModel(array('success' => true, 'added' => $added, 'contact'=>$uuid));
    }
    public function deleteOneAction() {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');
        $type = $this->params()->fromRoute('type', 'received');
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $message = $entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new \Zend\View\Model\JsonModel(array('success' => false));
        }
        if (($type=='received' && $user != $message->getToUsr()) || 
            ($type=='sent' && $user != $message->getFromUsr()) ||
            ($type=='trash' && $user != $message->getToUsr() && $user != $message->getFromUsr())) {
            return new \Zend\View\Model\JsonModel(array('success' => false));
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
        $entityManager->merge($message);
        $entityManager->flush();
        return new \Zend\View\Model\JsonModel(array('success' => true, 'trash' => $trash, 'type' => $type));
    }
    public function deleteSelectedAction() {
        $user = $this->identity();
        $request = $this->getRequest();
        $mails =$request->isPost()? $this->params()->fromPost('mail') :  $this->params()->fromQuery('mail');
        $type = $this->params()->fromRoute('type', 'received');
        $errors = [];
        $messages = [];
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $messageNotExists = "One or more mails do not exists.";
        $messageNotOwner = "You are not authorised to delete one or more messages.";
        // verify if messages exist and the user can delete
        foreach ($mails as $mail) {
            $message = $entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $mail]);
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
                    $trash = false;
                }
                $entityManager->merge($message);
            }
            $entityManager->flush();
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
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $inboxMessage = $entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$inboxMessage || ($inboxMessage->getToUsr()!=$user && $inboxMessage->getFromUsr()!=$user)) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        $groupMessages = $entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $inboxMessage->getIbxGroup()]);
        $toUsers = array();
        foreach ($groupMessages as $message) {
            $toUsers[] = $message->getToUsr();
        }
        $unreadMessage = false;
        if (!$inboxMessage->getIbxViewed() && $type=='received') {
            $inboxMessage->setIbxViewed(1);
            $entityManager->merge($inboxMessage);
            $entityManager->flush();
            $unreadMessage = true;
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'unreadMessage' => $unreadMessage,
            'message' => $inboxMessage,
            'toUsers' => $toUsers,
            'type' => $type,
            'user' => $user
        ]);
        return $viewModel;
    }
    public function createMessageAction() {
        $to = $this->params('to');
        $type = $this->params('type', 0);
        $uuid = $this->params('uuid', '00000000-0000-0000-0000-000000000000');
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $config = $this->getServiceLocator()->get('Config');
        $toUsr = $entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrName' => $to]);
        $group = uniqid('', true);
        $message = new Inbox();
        $message->setToUsr($toUsr)
                ->setIbxType($type)
                ->setIbxGroup($group);
        switch ($type) {
            case $config['demodyne']['inbox']['type']['new_comment']:
                $comment = $entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID' => $uuid]);
                $message->setCom($comment)
                        ->setIbxTitle($comment->getProp()?_('Proposal: ').$comment->getProp()->getPropName():($comment->getProgram()?_('Program: ').$comment->getProgram()->getProgName():'Comment: to add comment ref'))
                        ->setIbxText($comment->getComText())
                        ->setFromUsr($comment->getUsr());
                break;
            case $config['demodyne']['inbox']['type']['new_step']:
                break;
            case $config['demodyne']['inbox']['type']['champion_news']:
                break;
        }
        $entityManager->persist($message);
        $entityManager->flush();
        return true;
    }
    public function getContactsAction() {
        $user = $this->identity();
        $request = $this->getRequest();
            $contact = $this->params()->fromQuery('term');
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $contacts = $entityManager->getRepository('DGIModule\Entity\User')->getContacts($user, $contact);
            $contactList = array();
            foreach ($contacts as $contact) {
                $contactItem["value"] = $contact->getUsrName();
                $contactList[] = $contactItem;
            }
                return new \Zend\View\Model\JsonModel( $contactList );
    }
    public function newMessageAction()
    {
        $user = $this->identity();
        $to = $this->params()->fromRoute('to', '');
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $form = new NewMessageForm();
        $form->get('msgTo')->setValue($to);
        $request = $this->getRequest();
        $response = $this->getResponse();
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
                $config = $this->getServiceLocator()->get('Config');
                $toList = explode(',', preg_replace('/\s+/', '', $to));
                $messages = array();
                $toUsrList = array();
                foreach ($toList as $toValue) { // TODO: max 10 receivers by email
                    if ($toValue=='') continue;
                    $toUsr = $entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrName' => $toValue]); // @todo check only users from the same city? : pays
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
                                    ->setIbxType($config['demodyne']['inbox']['type']['private_message'])
                                    ->setIbxGroup($group);
                            $entityManager->persist($message);
                            // TODO: add receiver to contacts if not 
                        }
                        $entityManager->flush();
                    }
                    else {
                        $comment = $entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID' => $com]);
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
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $message = $entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new \Zend\View\Model\JsonModel(array('success' => false));
        }
        if ($user != $message->getToUsr() || $message->getIbxToDeletedDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog' => true));
        }
        $form = new NewMessageForm();
        $form->get('msgTo')->setValue($message->getFromUsr()->getUsrName());
        $form->get('msgTitle')->setValue('Re: '.$message->getIbxTitle());
        $msg = '<br><br>On '.$message->getIbxCreatedDate()->format("d/m/Y H:i").', <span class="badge">'.$message->getFromUsr()->getUsrName().'</span> '._('wrote:').' <br><blockquote>'.$message->getIbxText().'</blockquote><br>';
        $config = $this->getServiceLocator()->get('Config');
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setTemplate('dgi-module/inbox/new-message.phtml');
        $viewModel->setVariables([
            'form'=>$form,
            'msg' => $msg
        ]);
        if ($message->getIbxType() == $config['demodyne']['inbox']['type']['new_comment']) {
            $viewModel->setVariable('comment', $message->getCom()->getComUUID());
            $form->get('msgTitle')->setValue($message->getIbxTitle());
        }
        return $viewModel;
    }
    public function replyAllAction()
    {
        $user = $this->identity();
        $uuid = $this->params()->fromRoute('id', '0');
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $message = $entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new \Zend\View\Model\JsonModel(array('success' => false));
        }
        if ($user != $message->getToUsr() || $message->getIbxToDeletedDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog' => $this->getRequest()->isXmlHttpRequest()));
        }
        $form = new NewMessageForm();
        $groupMessages = $entityManager->getRepository('DGIModule\Entity\Inbox')->findBy(['ibxGroup' => $message->getIbxGroup()]);
        $toUsers = '';
        foreach ($groupMessages as $msg) {
            $toUsers = $toUsers . $msg->getToUsr()->getUsrName();
            if ($msg !== end($groupMessages)) {
                $toUsers = $toUsers . ', ';
            }
        }
        $form->get('msgTo')->setValue($toUsers);
        $form->get('msgTitle')->setValue('Re: '.$message->getIbxTitle());
        $msg = '<br><br>On '.$message->getIbxCreatedDate()->format("d/m/Y H:i").', <span class="badge">'.$message->getFromUsr()->getUsrName().'</span> '._('wrote:').' <br><blockquote>'.$message->getIbxText().'</blockquote><br>';
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
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $message = $entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID' => $uuid]);
        if (!$message) {
            return new \Zend\View\Model\JsonModel(array('success' => false));
        }
        if ($user != $message->getToUsr() || $message->getIbxToDeletedDate()) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied', 'dialog' => true));
        }
        $form = new NewMessageForm();
        $form->get('msgTitle')->setValue('Fwd: '.$message->getIbxTitle());
        $msg = '<br><br>On '.$message->getIbxCreatedDate()->format("d/m/Y H:i").', <span class="badge">'.$message->getFromUsr()->getUsrName().'</span> '._('wrote:').' <br><blockquote>'.$message->getIbxText().'</blockquote><br>';
        $config = $this->getServiceLocator()->get('Config');
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