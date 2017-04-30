<?php/** * @link      https://github.com/demodyne/demodyne * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org) * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License */namespace DGIModule\Controller;use DGIModule\Entity\UserDigest;use DGIModule\Entity\Vote;use DGIModule\Form\ReferendumPartoutRegisterForm;use DGIModule\Form\UserNirForm;use Zend\Authentication\AuthenticationService;use Zend\Mvc\Controller\AbstractActionController;use Zend\Session\SessionManager;use Zend\View\Model\JsonModel;use Zend\View\Model\ViewModel;use Zend\Session\Container;use Doctrine\ORM\EntityManager;use Zend\Mvc\I18n\Translator;use DGIModule\Entity\Counters;use DGIModule\Entity\User;use Ramsey\Uuid\Uuid;class ReferendumController extends AbstractActionController{    protected $entityManager;    protected $translator;    protected $config;    protected $auth;    public function __construct(        array $config,        EntityManager $entityManager,        Translator $translator,        AuthenticationService $auth    )    {        $this->config = $config;        $this->entityManager = $entityManager;        $this->translator = $translator;        $this->auth = $auth;    }    public function indexAction()    {        $user = $this->identity();        if ($user) {            return $this->redirect()->toRoute('e-referendum', array('action'=>'vote'));        }        $viewModel = new ViewModel();        $registerSection = $this->forward()->dispatch('DGIModule\Controller\Referendum', array('action' => 'register'));        $viewModel->addChild($registerSection, 'registerSection');        $viewModel->setTerminal(true);        return $viewModel;    }    public function registerAction()	{	    unset($_SESSION['guest']);        /** @var $request \Zend\Http\Request */        $request = $this->getRequest();	    $viewModel = new ViewModel();	    $viewModel->setTerminal($request->isXmlHttpRequest());	    $france = $this->entityManager->getRepository('DGIModule\Entity\Country')->findOneBy(['countryId'=>73]);	    $postalcode = $this->params()->fromPost('usrPostalcode', null);		$form = new ReferendumPartoutRegisterForm($this->entityManager, $this->translator, $france, $postalcode);		if ($request->isPost()) {            $this->auth->clearIdentity();            $sessionManager = new SessionManager();            $sessionManager->forgetMe();            unset($_SESSION['level']);            unset($_SESSION['inbox']);            unset($_SESSION['administrationDashboard']);            unset($_SESSION['measure']);            unset($_SESSION['event']);            unset($_SESSION['news']);            unset($_SESSION['newsletter']);            unset($_SESSION['partnerDashboard']);            unset($_SESSION['proposal']);            unset($_SESSION['program']);            unset($_SESSION['banner']);            unset($_SESSION['session']);            unset($_SESSION['admin']);            unset($_SESSION['blog']);            unset($_SESSION['fb_access_token']);		    $user = new User();		    $form->bind($user);					$form->setData($request->getPost());		    if ($form->isValid()) {				$this->prepareData($user);				$this->entityManager->persist($user);                $this->entityManager->flush();                $this->entityManager->refresh($user);                $this->forward()->dispatch('DGIModule\Controller\Email', ['action' => 'email-validation', 'id'=>$user->getUsrUUID(), 'msg'=>'referendum', 'email' => true]);                $viewModel->setTemplate('dgi-module/referendum/registration-success.phtml');                $viewModel->setVariable('user', $user);				return $viewModel;			}				}		$viewModel->setVariables([		        'form' => $form,		    ]);		return $viewModel;			}		/**	 * Prepare the data for user registration	 * 	 * @param \DGIModule\Entity\User $user	 * @return \DGIModule\Entity\User	 */	public function prepareData($user)	{		$user->setUsrActive(0);		$user->setUsrEmailConfirmed(0);		$user->setUsrPasswordSalt($this->generateDynamicSalt());						$user->setUsrPassword($this->encriptPassword(								$this->getStaticSalt(), 								$user->getUsrPassword(), 								$user->getUsrPasswordSalt()		));		$user->setUsrlId($this->config['demodyne']['account']['type']['member']);		$session = new Container('language');		$language = $this->entityManager->getRepository('DGIModule\Entity\Language')->findOneBy(['langId'=>$session->language]);		$user->setLang($language);        $now =  new \DateTime();		$user->setUsrPicture('/img/avatar/avatar.png');		$user->setUsrRegistrationDate($now);		$user->setUsrRegistrationToken(md5(uniqid(mt_rand(), true))); 		$user->setUsrCurrentLoginDate($now);		$user->setUsrUUID(Uuid::uuid4());				$cnt = new Counters();		$cnt->setCntUpdatedDate(new \DateTime());		$user->setCounters($cnt);		$digest = new UserDigest();		$user->setDigest($digest);				return $user;	}		/**	 * Generate dynamic salt for password encryption	 *	 * @return string	 */    public function generateDynamicSalt()    {		$dynamicSalt = '';		for ($i = 0; $i < 50; $i++) {			$dynamicSalt .= chr(rand(33, 126));		}        return $dynamicSalt;    }    /**     * Get the static salt for password encryption from local configuration     *     * @return string     */    public function getStaticSalt()    {		$staticSalt = $this->config['demodyne']['registration']['password_static_salt'];        return $staticSalt;    }    /**     *  Password encryption     *     * @param string $staticSalt     * @param string $password     * @param string $dynamicSalt     * @return string     */    public function encriptPassword($staticSalt, $password, $dynamicSalt)    {		return $password = md5($staticSalt . $password . $dynamicSalt);    }    public function userNirAction()    {        $user = $this->identity();        $viewModel = new ViewModel();        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());        $form = new UserNirForm($this->entityManager, $this->translator, $user);        $request = $this->getRequest();        if ($request->isPost()) {            $form->bind($user);            $form->setData($request->getPost());            if ($form->isValid()) {                $this->entityManager->merge($user);                $this->entityManager->flush();                return new JsonModel(['success'=>true]);            }        }        $viewModel->setVariables([            'form' => $form,            'user' => $user        ]);        return $viewModel;    }    public function voteAction()    {        $user = $this->identity();        $viewModel = new ViewModel();        $viewModel->setTerminal(true);        $voteValue = (int)$this->params()->fromRoute('vote', null);        if ($voteValue && !in_array($voteValue, [-1, 0, 1], true)) {            return $this->forward()->dispatch('DGIModule\Controller\Error',                [                    'action' => 'error',                    'message' => $this->translator->translate('An error has occurred. Please try again later.Vote error', 'DGIModule')]);        }        $refUser = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrName'=>'referendum']);        if (!$refUser) {            return $this->forward()->dispatch('DGIModule\Controller\Error',                [                    'action' => 'error', 'dialog'=>true,                    'message' => $this->translator->translate('An error has occurred. Please try again later.User not found.', 'DGIModule')]);        }        $refProp = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propSavedName'=>'Referendum', 'usr'=>$refUser]);        if (!$refProp) {            return $this->forward()->dispatch('DGIModule\Controller\Error',                [                    'action' => 'error', 'dialog'=>true,                    'message' => $this->translator->translate('An error has occurred. Please try again later.Referendum not found.', 'DGIModule')]);        }        $alreadyVoted = $this->entityManager->getRepository('DGIModule\Entity\Vote')->findOneBy(['prop'=>$refProp, 'usr'=>$user]);        if (!$alreadyVoted && $voteValue) {            $now = new \DateTime();            $vote = new Vote();            $vote->setVoteVote(5*$voteValue)                ->setVoteCreatedDate($now)                ->setProp($refProp)                ->setUsr($refUser)                ->setVoteUUID(Uuid::uuid4())            ;            $this->entityManager->persist($vote);            $hasVoted = new Vote();            $hasVoted->setVoteVote(0)                ->setVoteCreatedDate($now)                ->setProp($refProp)                ->setUsr($user)                ->setVoteUUID(Uuid::uuid4())            ;            $this->entityManager->persist($hasVoted);            $this->entityManager->flush();            return $this->redirect()->toRoute('e-referendum', array('action'=>'vote'));        }        if ($alreadyVoted) {            $viewModel->setTemplate('dgi-module/referendum/vote-success.phtml');            $voteResults = $this->entityManager->getRepository('DGIModule\Entity\Vote')->getReferendumValue($refUser, $refProp);            $viewModel->setVariables([                'voteResults' => $voteResults            ]);        }        $viewModel->setVariables([            'user' => $user        ]);        return $viewModel;    }}