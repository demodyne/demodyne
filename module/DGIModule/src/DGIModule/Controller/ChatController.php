<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */
 
namespace DGIModule\Controller;

use DGIModule\Entity\ChatMessage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use DGIModule\Entity\BlockedUser;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class ChatController extends AbstractActionController
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
    
    public function viewChatAction()
    {
        $user = $this->identity();
        $chatUUID = $this->params('id');
        $input = $this->params()->fromRoute('input', true);
        // no parameter
        if (! $chatUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }

        $chat = $this->entityManager->getRepository('DGIModule\Entity\Chat')->findOneBy(['chatUUID' => $chatUUID]);
        
        if (! $chat ) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', ['action' => 'access-denied']);
        }
         
        $viewModel = new ViewModel();
        // disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());

        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/chat/view-chat.mobile.phtml');
        }
        
        $viewModel->setVariables([
            'chat' => $chat,
            'user' => $user,
            'input' => $input
        ]);
        return $viewModel;
    }
    
    public function setTitleAction()
    {
        $user = $this->identity();
    
        $chatUUID = $this->params('id', null);
        // no parameter
        if (! $chatUUID) {
            return new JsonModel(['success' => false]);
        }
    
        $chat = $this->entityManager->getRepository('DGIModule\Entity\Chat')->findOneBy(['chatUUID' => $chatUUID]);
    
        if (! $chat || $chat->getUsr()!=$user) {
            return new JsonModel(['success' => false]);
        }
    
        $request = $this->getRequest();
        if ($request->isPost()) {
    
            $title = $this->params()->fromPost('title');
    
            if ($title) {
                $chat->setChatTitle($title);
                $this->entityManager->merge($chat);
                $this->entityManager->flush();
            }
    
            return new JsonModel(['success' => true]);
             
        }
        return new JsonModel(['success' => false]);
    }
    
    public function addMessageAction()
    {
        $user = $this->identity();
        
        $chatUUID = $this->params('id', null);
        // no parameter
        if (! $chatUUID) {
            return new JsonModel(['success' => false]);
        }
        
        $chat = $this->entityManager->getRepository('DGIModule\Entity\Chat')->findOneBy(['chatUUID' => $chatUUID]);
        
        if (! $chat ) {
            return new JsonModel(['success' => false]);
        }
        
        // check if user is blocked
        $query = $this->entityManager->createQuery('SELECT b FROM DGIModule\Entity\BlockedUser b
                                               WHERE b.usr=:user AND b.entityUUID=:uuid')
                               ->setParameter('user', $user)
                               ->setParameter('uuid', $chatUUID);
        $bu = $query->getOneOrNullResult();
        if ($bu) {
            return new JsonModel(['success' => false, 'message' => 'You are blocked by the owner']);
        }
        
        $request = $this->getRequest();
        if ($request->isPost()) {
    
            $message = new ChatMessage();
            
            $uuid = $this->params()->fromPost('id');
            
            if ($uuid) {
                $entity = $this->params()->fromPost('entity');
                if ($entity=='idea') {
                    $idea = $this->entityManager->getRepository('DGIModule\Entity\Idea')->findOneBy(['ideaUUID' => $uuid]);
                    if ($idea) {
                        $message->setMsgEntityName($idea->getIdeaName());
                    }
                }
            }
            
            $message->setChat($chat)
                    ->setUsr($user)
                    ->setMsgText($this->params()->fromPost('message'))
                    ->setMsgEntityUUID($uuid!=''?$uuid:null)
                    ->setMsgDateTime(new \DateTime())
                    ;
            
             $this->entityManager->persist($message);
             $this->entityManager->flush();
             return new JsonModel(['success' => true]);
                   
        }
        return new JsonModel(['success' => false]);
    }
    
    public function messageListAction()
    {
        $user = $this->identity();
        
        $chatUUID = $this->params('id');
        // no parameter
        if (! $chatUUID) {
            return new JsonModel(['messages' => []]);
        }
        
        $chat = $this->entityManager->getRepository('DGIModule\Entity\Chat')->findOneBy(['chatUUID' => $chatUUID]);
        
        if (! $chat ) {
            return new JsonModel(['messages' => []]);
        }
        
        $uuid = $this->params()->fromQuery('id');
    
        $messages = $this->entityManager->getRepository('DGIModule\Entity\ChatMessage')->getMessageList($chat, $uuid);

        $messagesJson = [];
        if (count($messages)) {
            $messageItem = ['message' => ''];
            $i=0;
            foreach ($messages as $index => $message) {
                $usr = $message->getUsr();
                $blockedUsr = $message->getBlockedUsr();
                if ($blockedUsr) {
                    if ($messageItem) {
                        $messagesJson[] = $messageItem;
                    }
                    $messagesJson[] = [
                        'user' => [
                            'name' => $usr->getUsrName(),
                            'uuid' => $usr->getUsrUUID(),
                            'picture' => $usr->getUsrPicture(),
                            'me' => $usr==$user,
                        ],
                        'blockedUser' => [
                            'name' => $blockedUsr->getUsrName(),
                            'uuid' => $blockedUsr->getUsrUUID(),
                            'picture' => $blockedUsr->getUsrPicture(),
                            'me' => $blockedUsr==$user,
                        ],
                        'message' => $message->getMsgText(),
                        'uuid' => $message->getMsgEntityUUID(),
                        'name' => $message->getMsgEntityName(),
                        'date' => $message->getMsgDateTime()->format('H:i:s'),
                    ];
                    $messageItem = null;
                }
                else { 
                    if ($index && ($usr->getUsrUUID()!=$messageItem['user']['uuid'] || $message->getMsgEntityUUID()!=$messageItem['uuid'] || $i>4)) {
                        if ($messageItem) {
                            $messagesJson[] = $messageItem;
                        }
                        
                        $messageItem = [
                            'user' => [
                                'name' => $usr->getUsrName(),
                                'uuid' => $usr->getUsrUUID(),
                                'picture' => $usr->getUsrPicture(),
                                'me' => $usr==$user,
                            ],
                            'blockedUser' => null,
                            'message' => $message->getMsgText(),
                            'uuid' => $message->getMsgEntityUUID(),
                            'name' => $message->getMsgEntityName(),
                            'date' => $message->getMsgDateTime()->format('H:i:s'),
                        ];
                        $i=1;
                    }
                    else {
                        $messageItem = [
                            'user' => [
                                'name' => $usr->getUsrName(),
                                'uuid' => $usr->getUsrUUID(),
                                'picture' => $usr->getUsrPicture(),
                                'me' => $usr==$user,
                            ],
                            'blockedUser' => null,
                            'message' => $messageItem['message']."<br>".$message->getMsgText(),
                            'uuid' => $message->getMsgEntityUUID(),
                            'name' => $message->getMsgEntityName(),
                            'date' => $message->getMsgDateTime()->format('H:i:s'),
                        ];
                        $i++;
                    }
                }
            }
            if ($messageItem) {
                $messagesJson[] = $messageItem;
            }
        }
        
        return new JsonModel(['messages' => $messagesJson, 'title' => $chat->getChatTitle()]);
        
    }

    public function blockAction()
    {
        $user = $this->identity();
    
        $chatUUID = $this->params('id', null);
        // no parameter
        if (! $chatUUID) {
            return new JsonModel(['success' => false, 'message' => 'No chat found!']);
        }
    
        $chat = $this->entityManager->getRepository('DGIModule\Entity\Chat')->findOneBy(['chatUUID' => $chatUUID]);
    
        if (! $chat || $chat->getUsr()!=$user) {
            return new JsonModel(['success' => false, 'message' => 'No chat found!']);
        }
    
        $request = $this->getRequest();
        if ($request->isPost()) {
    
            $userUUID = $this->params()->fromRoute('user');
            // search for the user to block
            $blockedUser = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID' => $userUUID]);
            
            if (!$blockedUser) {
                return new JsonModel(['success' => false, 'message' => 'No user found!']);
            }
    
            // search if already blocked
            $query = $this->entityManager->createQuery('SELECT b FROM DGIModule\Entity\BlockedUser b
                                               WHERE b.usr=:user AND b.entityUUID=:uuid')
                                   ->setParameter('user', $blockedUser)
                                    ->setParameter('uuid', $chatUUID);
            $bu = $query->getOneOrNullResult();
            
            if ($bu) {
                $this->entityManager->remove($bu);
                $added = false;
                
                $message = new ChatMessage();
                
                $message->setChat($chat)
                        ->setUsr($user)
                        ->setMsgText('false')
                        ->setBlockedUsr($blockedUser)
                        ->setMsgDateTime(new \DateTime())
                ;
                
                $this->entityManager->persist($message);
                
            }
            else {
                $bu = new BlockedUser();
                $bu->setEntityUUID($chatUUID)
                   ->setEntityType('chat')
                   ->setUsr($blockedUser);
                $this->entityManager->persist($bu);
                $added = true;
                
                $message = new ChatMessage();
                
                $message->setChat($chat)
                    ->setUsr($user)
                    ->setMsgText('true')
                    ->setBlockedUsr($blockedUser)
                    ->setMsgDateTime(new \DateTime())
                ;
                
                $this->entityManager->persist($message);
            }
            
            // search if already blocked for the entity
            $query = $this->entityManager->createQuery('SELECT b FROM DGIModule\Entity\BlockedUser b
                                               WHERE b.usr=:user AND b.entityUUID=:uuid')
                                   ->setParameter('user', $blockedUser)
                                   ->setParameter('uuid', $chat->getChatEntityUUID());
            $buEntity = $query->getOneOrNullResult();

            if ($buEntity) {
                $this->entityManager->remove($buEntity);
            }
            else {
                $buEntity = new BlockedUser();
                $buEntity->setEntityUUID($chat->getChatEntityUUID())
                    ->setEntityType($chat->getChatEntityType())
                    ->setUsr($blockedUser);
                $this->entityManager->persist($buEntity);
            }
            
            $this->entityManager->flush();
            
            return new JsonModel(['success' => true, 'added' => $added]);
             
        }
        return new JsonModel(['success' => false]);
    }
}