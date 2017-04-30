<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use DGIModule\Entity\Comment;
use DGIModule\Form\AddCommentForm;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;


class CommentController extends AbstractActionController
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
    
    public function addCommentAction()
    {
        $user = $this->identity();
        
        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', 0);
        $ajax = $this->params()->fromRoute('ajax', true);

        $proposal = null;
        if ($type=='proposal') {
            $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$UUID]);
        }

        $form       = new AddCommentForm();
        $form->setHydrator(new DoctrineHydrator($this->entityManager,'DGIModule\Entity\Comment'));

        $comText = $this->params()->fromPost('comText');
        
        $request = $this->getRequest();
        if ($ajax && $request->isPost()){
            
            $comment = new Comment();
            $form->bind($comment);
            
            $form->setData($request->getPost());
            
            if ($form->isValid()){
                // prepare data
                switch ($type) {
                    case 'program': 
                        $program = $this->entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(['progUUID'=>$UUID]);
                        $comment->setProgram($program); 
                        $to = $program->getUsr();
                        break;
                    case 'proposal': 
                        $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$UUID]);
                        $comment->setProp($proposal); 
                        $to = $proposal->getUsr();
                        break;
                    case 'article':
                        $article = $this->entityManager->getRepository('DGIModule\Entity\Article')->findOneBy(['articleUUID'=>$UUID]);
                        $comment->setArticle($article);
                        $to = $article->getUsr();
                        break;
                }
                
                $comment->setUsr($user)
                        ->setComCreatedDate(new \DateTime())
                        ->setComUUID(Uuid::uuid4())
                ;
                
                $this->entityManager->persist($comment);
                
                $counters = $user->getCounters();
                if ($counters->getCntCom()<5) {
                    $counters->setCntTotal($counters->getCntTotal()+2)
                             ->setCntCom($counters->getCntCom()+1);
                    $this->entityManager->merge($counters);
                }
                
                $this->entityManager->flush();
                $this->entityManager->refresh($comment);
                
                $this->forward()->dispatch('DGIModule\Controller\Inbox', [
                            'action' => 'create-message',
                            'to' => $to->getUsrName(),
                            'type' => $this->config['demodyne']['inbox']['type']['new_comment'],
                            'uuid' => $comment->getComUUID(),
                ]);

                if ($to->getDigest()->getDigestAlertComments()==$this->config['demodyne']['email']['alert']['instant']) {
                    $this->forward()->dispatch('DGIModule\Controller\Email', [
                        'action' => 'new-comment',
                        'id' => $comment->getComUUID(),
                        'email' => true
                    ]);
                }

                $comText = '';
            }
        }
        $commentListSection = $this->forward()->dispatch('DGIModule\Controller\Comment', ['action' => 'list', 'type' =>$type,  'id' => $UUID, 'ajax'=>false]);
        $viewModel = new ViewModel();
        $viewModel->addChild($commentListSection, 'commentListSection');
        
        if ($ajax) $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        $form->get('comText')->setValue('');
        
        $viewModel->setVariables([
            'type'   => $type,
            'id'  => $UUID,
            'commentForm'   => $form,
            'user'          => $user,
            'comText' => $comText,
            'proposal' => $proposal
        ]);
        
        return $viewModel;
                
    }
    
    public function createCommentAction()
    {
        $user = $this->identity();
    
        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', 0);
        $com = $this->params()->fromRoute('com', '');

        $comment = new Comment();

        if ($type=='program') {
            $program = $this->entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(['progUUID'=>$UUID]); 
            $comment->setScn($program);   
            $to = $program->getUsr();
        }
        elseif ($type=='proposal') {
            $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$UUID]); 
            $comment->setProp($proposal);
            $to = $proposal->getUsr();
        }
    
        $comment->setUsr($user)
                ->setComText($com)
                ->setComCreatedDate(new \DateTime())
                ->setComUUID(Uuid::uuid4());
        
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        $this->entityManager->refresh($comment);
        
        $this->forward()->dispatch('DGIModule\Controller\Inbox', array(
            'action' => 'create-message',
            'to' => $to->getUsrName(),
            'type' => $this->config['demodyne']['inbox']['type']['new_comment'],
            'uuid' => $comment->getComUUID(),
        ));

        if ($to->getDigest()->getDigestAlertComments()==$this->config['demodyne']['email']['alert']['instant']) {
            $this->forward()->dispatch('DGIModule\Controller\Email', array(
                'action' => 'new-comment',
                'id' => $comment->getComUUID(),
                'email' => true
            ));
        }
    
        return true;
    }
    
    public function listAction() {
        $user = $this->identity();
    
        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', '0');
        $actions = $this->params()->fromRoute('actions', 'true');
	    
        $page = $this->params()->fromRoute('page', 1);
        $ajax = $this->params()->fromRoute('ajax', true);
        $limit= $this->params()->fromRoute('results', 10);
    
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;

        $pagedComments = $this->entityManager->getRepository('DGIModule\Entity\Comment')->getPagedComments($type, $UUID, $offset, $limit);
        
        $viewModel = new ViewModel();
        
        if ($ajax) {
            $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        }
        
        if (isset($_SESSION['mobile']) && $_SESSION['mobile']) {
            $viewModel->setTemplate('dgi-module/comment/list.mobile.phtml');
        }

        if ($actions=='false') {
            $viewModel->setTemplate('dgi-module/comment/list-no-actions.phtml');
        }

        $viewModel->setVariables([
            'pagedComments' => $pagedComments,
            'limit' => $limit,
            'page' => $page,
            'type' => $type,
            'UUID' => $UUID,
            'user' => $user,
        ]);
         
        return $viewModel;
    }
    
}