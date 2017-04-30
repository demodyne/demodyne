<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Filter\File\Rename;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DGIModule\Entity\Report;
use DGIModule\Form\AddEditBugForm;
use DGIModule\Entity\Bug;
use DGIModule\Entity\Inbox;
use DGIModule\Form\AddReportForm;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class ReportController extends AbstractActionController
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
    
    public function addReportAction()
    {
        $user = $this->identity();

        $type = $this->params()->fromRoute('type');
        $UUID = $this->params()->fromRoute('id', 0);
        
        $viewModel = new ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        $comment = null;
        $proposal = null;
        $program = null;
        $inbox = null;
        
        if ($type == 'comment') {
            $comment = $this->entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(array('comUUID' => $UUID));
        }
        elseif ($type == 'proposal') {
            $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $UUID));
        }
        elseif ($type == 'program') {
            $program = $this->entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(array('progUUID' => $UUID));
        }
        elseif ($type == 'inbox') {
            $inbox = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(array('ibxUUID' => $UUID));
        }
        
        $report = $this->entityManager->getRepository('DGIModule\Entity\Report')->findOneBy(array('repUUID' => $UUID, 'repType' => $type, 'usr' => $user));

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
            $form = new AddReportForm($this->translator);
            $form->setAttribute('action', $this->url()->fromRoute('home/report', array('action'=>'add-report', 'type' => $type, 'id' => $UUID)));
            $request = $this->getRequest();
            if ($request->isPost()) {
                
                $form->bind($report);
                $form->setData($request->getPost());
                if ($form->isValid()) {
                
                    $report->setUsr($user)
                            ->setRepType($type)
                            ->setRepUUID($UUID)
                            ->setRepCreatedDate(new \DateTime())
                    ;
                    $this->entityManager->persist($report);
                    $this->entityManager->flush();
                    $this->entityManager->refresh($report);

                    // send email to bug-hunter@demodyne.org
                    $this->forward()->dispatch('DGIModule\Controller\Email', array(
                        'action' => 'new-moderation-report',
                        'id' => $report->getRepId(),
                        'email' => true
                    ));

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
        
        $user = $this->identity();
        
        $viewModel = new ViewModel();

        $bug = new Bug();
        $form = new AddEditBugForm($this->translator);
        $form->setAttribute('action', $this->url()->fromRoute('home/report', array('action'=>'submit-bug')));
        $request = $this->getRequest();
        $msg = $this->params()->fromPost('bugDescription', '');
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
                );
            $form->bind($bug);
            $form->setData($post);
            //verify if picture change
            $picture = $post["bugImage"]["name"];
            if ($form->isValid()) {
                if ($picture!="") {
                    $files   = $request->getFiles();
                    $target = getcwd() . "/public/img/bugs/bug.jpg";
                    $filterR = new Rename(array(
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
                $bug->setUsr($user)
                    ->setBugCreatedDate(new \DateTime());
                $this->entityManager->persist($bug);
                $this->entityManager->flush();
                $this->entityManager->refresh($bug);
                
                
                // send private message to user
                $bugHunter =  $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrName'=>'bug-hunter']);
                $message = new Inbox();
                $message->setToUsr($user)
                        ->setFromUsr($bugHunter)
                        ->setIbxTitle('Bug submission - '.$bug->getBugTitle())
                        ->setIbxText($this->translator->translate('Thank you for submitting the following bug:', 'DGIModule').'<br><br><blockquote>'.
                            $bug->getBugDescription().'</blockquote><br><br>'.
                            $this->translator->translate('We will do our best to solve it quickly.<br><br>Thank you,<br>The Demodyne Team', 'DGIModule'))
                        ->setIbxType($this->config['demodyne']['inbox']['type']['private_message'])
                        ->setIbxGroup(uniqid('', true));
                $this->entityManager->persist($message);
                
                $this->entityManager->flush();
                
                $this->entityManager->refresh($bug);
                
                // send email to bug-hunter@demodyne.org
                $this->forward()->dispatch('DGIModule\Controller\Email', array(
                    'action' => 'new-bug',
                    'id' => $bug->getBugId(),
                    'email' => true
                ));

                $viewModel->setTemplate('dgi-module/report/submit-bug-success.phtml');
            }
        }

        // disable layout if request by Ajax
        $viewModel->setTerminal($request->isXmlHttpRequest());
        $viewModel->setVariables([
            'form'=>$form,
            'user' => $user,
            'msg' => $msg
        ]);
        return $viewModel;
    }
    
   
}