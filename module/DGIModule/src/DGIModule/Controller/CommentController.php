<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use DGIModule\Entity\Comment;
use DGIModule\Form\AddCommentForm;

class CommentController extends AbstractActionController
{
    
    public function addCommentAction()
    {
        $user = $this->identity();
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', 0);
        $ajax = $this->params()->fromRoute('ajax', true);
        
        $proposal = null;
        if ($type=='proposal') {
            $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$UUID]);
        }
        
        $form       = new AddCommentForm();
        $form->setHydrator(new DoctrineHydrator($entityManager,'DGIModule\Entity\Comment'));
        
        
        $comText = $this->params()->fromPost('comText');
        
        $request = $this->getRequest();
        if ($ajax && $request->isPost()){
            
            $comment = new Comment();
            $form->bind($comment);
            
            $form->setData($request->getPost());
            
            if ($form->isValid()){
                // prepare data
                $config = $this->getServiceLocator()->get('Config');
                
                
                switch ($type) {
                    case 'program': 
                        $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(['progUUID'=>$UUID]);
                        $comment->setProgram($program); 
                        $to = $program->getUsr()->getUsrName();
                        break;
                    case 'proposal': 
                        $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$UUID]);
                        $comment->setProp($proposal); 
                        $to = $proposal->getUsr()->getUsrName();
                        break;
                }
                
                $comment->setUsr($user);
                
                $entityManager->persist($comment);
                
                $counters = $user->getCounters();
                if ($counters->getCntCom()<5) {
                    $counters->setCntTotal($counters->getCntTotal()+2)
                             ->setCntCom($counters->getCntCom()+1);
                    $entityManager->merge($counters);
                }
                
                $entityManager->flush();
                $entityManager->refresh($comment);
                
                $this->forward()->dispatch('DGIModule\Controller\Inbox', array(
                            'action' => 'create-message',
                            'to' => $to,
                            'type' => $config['demodyne']['inbox']['type']['new_comment'],
                            'uuid' => $comment->getComUUID(),
                        ));
                
                $comText = '';
            }
        }
        $commentListSection = $this->forward()->dispatch('DGIModule\Controller\Comment', array('action' => 'list', 'type' =>$type,  'id' => $UUID, 'ajax'=>false));
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
    
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    
        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', 0);
        $com = $this->params()->fromRoute('com', '');
        
        var_dump($com);
        
        $config = $this->getServiceLocator()->get('Config');
        
        $comment = new Comment();
         
        if ($type=='program') {
            $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(['progUUID'=>$UUID]); 
            $comment->setScn($program);   
            $to = $program->getUsr()->getUsrName();
        }
        elseif ($type=='proposal') {
            $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$UUID]); 
            $comment->setProp($proposal);
            $to = $proposal->getUsr()->getUsrName();
        }
    
        $comment->setUsr($user)->setComText($com);
        
        $entityManager->persist($comment);
        $entityManager->flush();
        $entityManager->refresh($comment);
        
        $this->forward()->dispatch('DGIModule\Controller\Inbox', array(
            'action' => 'create-message',
            'to' => $to,
            'type' => $config['demodyne']['inbox']['type']['new_comment'],
            'uuid' => $comment->getComUUID(),
        ));
    
        return true;
    }
    
    public function listAction() {
        $user = $this->identity();
    
        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', '0');
        $actions = $this->params()->fromRoute('actions', 'true');
	    
        $page = $this->params()->fromRoute('page', 1);
        $ajax = $this->params()->fromRoute('ajax', true);
        $limit= $this->params()->fromRoute('results', 5);
    
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;

        $pagedComments = $entityManager->getRepository('DGIModule\Entity\Comment')->getPagedComments($type, $UUID, $offset, $limit);
        
        $viewModel = new ViewModel();
        
        if ($ajax) {
            $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
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