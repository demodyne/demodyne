<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use DGIModule\Entity\Report;
use DGIModule\Form\AddEditBugForm;
use DGIModule\Entity\Bug;
use DGIModule\Entity\Inbox;
use DGIModule\Form\AddReportForm;

class ReportController extends AbstractActionController
{
    public function addReportAction()
    {
        $user = $this->identity();
       
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', 0);
        
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        $comment = null;
        $proposal = null;
        $program = null;
        $inbox = null;
        
        if ($type == 'comment') {
            $comment = $entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(array('comUUID' => $UUID));
        }
        elseif ($type == 'proposal') {
            $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $UUID));
        }
        elseif ($type == 'program') {
            $program = $entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $UUID));
        }
        elseif ($type == 'inbox') {
            $inbox = $entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(array('ibxUUID' => $UUID));
        }
        
        $report = $entityManager->getRepository('DGIModule\Entity\Report')->findOneBy(array('repUUID' => $UUID, 'repType' => $type, 'usr' => $user));

        if ($report) {
            $viewModel->setVariables([
                'user' => $user,
                'comment' => $comment,
                'proposal' => $proposal,
                'scenario' => $program,
                'inbox' => $inbox,
                'type' => $type,
                'alreadyReported' => true
            ]);
        }
        else {
            $report = new Report();
            $form = new AddReportForm();
            $form->setAttribute('action', $this->url()->fromRoute('home/report', array('action'=>'add-report', 'type' => $type, 'id' => $UUID)));
            $request = $this->getRequest();
            if ($request->isPost()) {
                
                $form->bind($report);
                $form->setData($request->getPost());
                if ($form->isValid()) {
                
                    $report->setUsr($user)
                            ->setRepType($type)
                            ->setRepUUID($UUID);
                    $entityManager->persist($report);
                    $entityManager->flush();
                    
                    $now = new \DateTime(); 
                    if ($type == 'comment') {
                         $text = $comment->getComText();
                         $owner = $comment->getUsr();
                    }
                    elseif ($type == 'proposal') {
                        $text = '<a href="https://www.demodyne.org'.$this->url()->fromRoute('proposal', array('action' => 'view' ,'id'=>$proposal->getPropUUID())).'">'.$proposal->getPropSavedName().'</a>';
                         $owner = $proposal->getUsr();
                    }
                    elseif ($type == 'program') {
                        $text = '<a href="https://www.demodyne.org'.$this->url()->fromRoute('scenario', array('action' => 'view' ,'id'=>$program->getScnUUID())).'">'.$scenario->getScnName().'</a>';
                         $owner = $program->getUsr();
                    }
                    $reason = $this->params()->fromPost('repReason');
                    $description = $this->params()->fromPost('repDescription');
                    
                    // send email to bug-hunter@demodyne.org
                    $transport = $this->getServiceLocator()->get('mail.transport');
                    $message = new Message();
                    
                    $html = new MimePart('User '.$user->getUsrName().' has submitted a moderation request on '.
                        $now->format("d/m/Y H:i").'<br>Reason: '.$reason.' <br>Description:'.$description.'<br> on '.$type.':<br><br><blockquote>'.$text.'<br>by '.$owner->getUsrName().'</blockquote><br>');
                    $html->type = "text/html";
                    
                    $body = new MimeMessage();
                    $body->setParts(array($html));
                    
                    $this->getRequest()->getServer();  //Server vars
                    $message->addTo('moderation@demodyne.org')
                            ->addFrom('moderation@demodyne.org')
                            ->setSubject('Moderation request on '.$type)
                            ->setBody($body);
                    $transport->send($message);
                    
                    $viewModel->setTemplate('dgi-module/report/add-report-success.phtml');
                }
            }
            
            $viewModel->setVariables([
                'user' => $user,
                'comment' => $comment,
                'proposal' => $proposal,
                'program' => $program, 
                'inbox' => $inbox,
                'type' => $type,
                'form' => $form,
                'alreadyReported' => false
            ]);
        }
        
        return $viewModel;
                
    }
    
    public function submitBugAction()
    {
        
        if (!$this->getRequest()->isXmlHttpRequest())
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        
        $user = $this->identity();
        
        $viewModel = new ViewModel();
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $bug = new Bug();
        $form = new AddEditBugForm();
        $form->setAttribute('action', $this->url()->fromRoute('home/report', array('action'=>'submit-bug')));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
                );
            $form->bind($bug);
            $form->setData($post);
            $picture = $post["bugImage"]["name"];
            if ($form->isValid()) {
                if ($picture!="") {
                    $files   = $request->getFiles();
                    $target = getcwd() . "/public/img/bugs/bug.jpg";
                    $filterR = new \Zend\Filter\File\Rename(array(
                        "target"    => $target,
                        "randomize" => true,
                    ));
                    $filename= $filterR->filter($files['bugImage']);
                    chmod($filename["tmp_name"], 0644);
                    $bug->setBugImage(str_replace(getcwd() . "/public", '', $filename["tmp_name"]));
                }
                else {
                    $bug->setBugImage(null);
                }
                $bug->setUsr($user);
                $entityManager->persist($bug);

                // send private message to user
                $config = $this->getServiceLocator()->get('Config');
                $bugHunter =  $entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrName'=>'bug-hunter']);
                $message = new Inbox();
                $message->setToUsr($user)
                        ->setFromUsr($bugHunter)
                        ->setIbxTitle('Bug submission - '.$bug->getBugTitle())
                        ->setIbxText(_('Thank you for submitting the following bug:').'<br><br><blockquote>'.$bug->getBugDescription().'</blockquote><br><br>'._('We will do our best to solve it quickly.<br><br>Thank you,<br>The Demodyne Team'))
                        ->setIbxType($config['demodyne']['inbox']['type']['private_message'])
                        ->setIbxGroup(uniqid('', true));
                $entityManager->persist($message);
                
                $entityManager->flush();
                
                $entityManager->refresh($bug);
                
                // send email to bug-hunter@demodyne.org
                $transport = $this->getServiceLocator()->get('mail.transport');
                $message = new Message();
                
                $html = new MimePart('User '.$user->getUsrName().' has submitted new bug on '.$bug->getBugCreatedDate()->format("d/m/Y H:i").':<br><br><blockquote>'.$bug->getBugDescription().'</blockquote><br><br>');
                $html->type = "text/html";
                
                $body = new MimeMessage();
                $body->setParts(array($html));
                
                $this->getRequest()->getServer();  //Server vars
                $message->addTo('support@demodyne.org')
                        ->addFrom($bugHunter->getUsrEmail())
                        ->setSubject('Bug submission - '.$bug->getBugTitle())
                        ->setBody($body);
                    $transport->send($message);
                    
                $viewModel->setTemplate('dgi-module/report/submit-bug-success.phtml');
            }
        }
        
        // disable layout if request by Ajax
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        $viewModel->setVariables([
            'form'=>$form,
            'user' => $user
        ]);
        return $viewModel;
    }
    
   
}
