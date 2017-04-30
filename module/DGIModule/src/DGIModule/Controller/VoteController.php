<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DGIModule\Entity\Vote;
use DGIModule\Form\AddEditVoteForm;
use Ramsey\Uuid\Uuid;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class VoteController extends AbstractActionController
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
	
    public function addAction()
    {
        $user = $this->identity();

        $propUUID = $this->params('id');
        $terminal = $this->params('terminal');
        
        // goto previous link
        if (!$propUUID)  return $this->redirect()->toUrl($this->getRequest()->getHeader('HTTP_REFERER'));
        
        $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $propUUID));
        
        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
            
        $owner = $proposal->getUsr()==$user;
        
        $vote = $this->entityManager->getRepository('DGIModule\Entity\Vote')->findOneBy(['prop'=>$proposal, 'usr'=>$user]);
        $voted = true;
        if (!$vote) {
            $vote = new Vote();
            $voted = false;
        }
        $voteForm = null;
        
        $voteForm = new AddEditVoteForm($this->translator);
        $voteForm->get('voteVote')->setValue($vote->getVoteVote());

        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $voteForm->bind($vote);
            $voteForm->setData($request->getPost());
           
            if ($voteForm->isValid()){

                // prepare data
                if (!$voted) {
                    $vote->setVoteCreatedDate(new \DateTime())
                         ->setProp($proposal)
                         ->setUsr($user)
                         ->setVoteUUID(Uuid::uuid4())
                    ;
                    $this->entityManager->persist($vote);
                    
                    $counters = $user->getCounters();
                    if ($counters->getCntVote()<5) {
                        $counters->setCntTotal($counters->getCntTotal()+1)
                                 ->setCntVote($counters->getCntVote()+1);
                        $this->entityManager->merge($counters);
                    }
                    $voted = true;
                }
                else {
                    $this->entityManager->merge($vote);
                }
                
                $this->entityManager->flush();
                
                $priority = $this->config['demodyne']['priority'];
                
                $score = 0;
                $voteAverage = $proposal->getVotesAverage();
                if ($voteAverage>=$this->config['demodyne']['vote']['average']) {
                    $included = false;
                    foreach ($proposal->getProposalPrograms() as $proposalProgram) {
                        $included = true;
                        $position = $proposalProgram->getSortPosition();
        	            $score += $position<11?$priority[$position]:$priority[11];
        	        }
        	        if ($included) {
            	        $score += 100;
            	        $topScore = $this->entityManager->getRepository('DGIModule\Entity\Vote')->getTopValue($proposal, $this->config['demodyne']['level']);
            	        $score = $topScore['toppoints'] * $score;
        	        }
                }
                
                $proposal->setPropAggregatedScore($score);
                
                $this->entityManager->merge($proposal);
                
                $this->entityManager->flush();
                
            }
           
        }

        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        if ($terminal) {
            $viewModel->setTerminal($request->isXmlHttpRequest());
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

        $propUUID = $this->params('id');
        $terminal = $this->params('terminal');
        $text = $this->params('text');
        
        // goto previous link
        if (!$propUUID) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        
        $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(array('propUUID' => $propUUID));
    
        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
    
        $voteValue = $this->entityManager->getRepository('DGIModule\Entity\Vote')->getVoteValue($proposal);
    
        $viewModel = new ViewModel();
        //disable layout if request by Ajax
        if ($terminal) {
            $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
        }
        
        $viewModel->setVariables([
            'proposal' => $proposal,
            'voteValue'     => $voteValue,
            'text' => $text
        ]);
         
        return $viewModel;
    }
    

    
}