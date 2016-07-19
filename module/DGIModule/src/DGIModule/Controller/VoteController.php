<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DGIModule\Entity\Vote;
use DGIModule\Entity\User;
use DGIModule\Form\AddEditVoteForm;

class VoteController extends AbstractActionController
{
	
    public function addAction()
    {
        $user = $this->identity();
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $propUUID = $this->params('id');
        $terminal = $this->params('terminal');
        
        // goto previous link
        if (!$propUUID)  return $this->redirect()->toUrl($this->getRequest()->getHeader('HTTP_REFERER'));
        
        $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $propUUID));
        
        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
            
        $owner = $proposal->getUsr()==$user;
        
        $vote = $entityManager->getRepository('DGIModule\Entity\Vote')->findOneBy(['prop'=>$proposal, 'usr'=>$user]);
        $voted = true;
        if (!$vote) {
            $vote = new Vote();
            $voted = false;
        }
        $voteForm = null;
        
        $voteForm = new AddEditVoteForm();
        $voteForm->get('voteVote')->setValue($vote->getVoteVote());
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $voteForm->bind($vote);
            $voteForm->setData($request->getPost());
           
            if ($voteForm->isValid()){

                // prepare data
                if (!$voted) {
                    $vote->setVoteCreatedDate(new \DateTime())
                         ->setProp($proposal)
                         ->setUsr($user);
                    $entityManager->persist($vote);
                    
                    $counters = $user->getCounters();
                    if ($counters->getCntVote()<5) {
                        $counters->setCntTotal($counters->getCntTotal()+1)
                                 ->setCntVote($counters->getCntVote()+1);
                        $entityManager->merge($counters);
                    }
                    $voted = true;
                }
                else {
                    $entityManager->merge($vote);
                }
                
                $entityManager->flush();
                
                $config = $this->getServiceLocator()->get('Config');
                $priority = $config['demodyne']['priority'];
                
                $score = 0;
                $voteAverage = $proposal->getVotesAverage();
                if ($voteAverage>=$config['demodyne']['vote']['average']) {
                    $included = false;
                    foreach ($proposal->getProposalPrograms() as $proposalProgram) {
                        $included = true;
                        $position = $proposalProgram->getSortPosition();
        	            $score += $position<11?$priority[$position]:$priority[11];
        	        }
        	        if ($included) {
            	        $score += 100;
            	        $topScore = $entityManager->getRepository('DGIModule\Entity\Vote')->getTopValue($proposal, $config['demodyne']['level']);
            	        $score = $topScore['toppoints'] * $score;
        	        }
                }
                
                $proposal->setPropAggregatedScore($score);
                
                $entityManager->merge($proposal);
                $entityManager->flush();
            }
        }
        
        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        if ($terminal) {
            $is_xmlhttprequest = $this->getRequest()->isXmlHttpRequest();
            $viewModel->setTerminal($is_xmlhttprequest);
        }
        
        $voteViewSection = $this->forward()->dispatch('DGIModule\Controller\Vote', array('action'=>'view', 'id' => $propUUID));
        $viewModel->addChild($voteViewSection, 'voteViewSection');
        
        $viewModel->setVariables([
            'proposal' => $proposal,
           'voteForm'      => $voteForm,
           'vote'          => $vote,
           'voted'         => $voted,
           'owner'         => $owner,
           'user'          => $user
        ]);
       
       return $viewModel;
    }
    
    public function viewAction()
    {
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    
        $propUUID = $this->params('id');
        $terminal = $this->params('terminal');
        $text = $this->params('text');
        
        // goto previous link
        if (!$propUUID)  
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied')); 
    
        $proposal = $entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $propUUID));
        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        
        $voteValue = $entityManager->getRepository('DGIModule\Entity\Vote')->getVoteValue($proposal);
    
        $viewmodel = new ViewModel();
        //disable layout if request by Ajax
        if ($terminal) {
            $is_xmlhttprequest = $this->getRequest()->isXmlHttpRequest();
            $viewmodel->setTerminal($is_xmlhttprequest);
        }
        
        $viewmodel->setVariables([
            'proposal' => $proposal,
            'voteValue'     => $voteValue,
            'text' => $text
        ]);
         
        return $viewmodel;
    }
    

    
}