<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use DGIModule\Entity\Administration;
use DGIModule\Entity\Bug;
use DGIModule\Entity\Report;
use DGIModule\Entity\User;
use Html2Text\Html2Text;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;
use Zend\View\Renderer\PhpRenderer;
use Zend\Mail\Transport\Smtp;

class EmailController extends AbstractActionController
{
    protected $entityManager;
    protected $translator;
    protected $config;
    protected $viewRenderer;
    protected $mailTransport;

    /**
     * EmailController constructor.
     * @param array $config
     * @param EntityManager $entityManager
     * @param Translator $translator
     * @param PhpRenderer $viewRenderer
     * @param Smtp $mailTransport
     */
    public function __construct(
        array $config,
        EntityManager $entityManager,
        Translator $translator,
        PhpRenderer $viewRenderer,
        Smtp $mailTransport
    )
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->viewRenderer = $viewRenderer;
        $this->mailTransport = $mailTransport;
    }
    
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $user = $this->identity();
        if (!$user || $user->getUsrlId()!=$this->config['demodyne']['account']['type']['admin']) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function emailValidationAction()
    {
        $userUUID = $this->params()->fromRoute('id', null);
        $msg = $this->params()->fromRoute('msg', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\User $user */
        $user = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID'=>$userUUID]);

        $viewModel = new ViewModel();
        if ($msg && $msg=='referendum') {
            $viewModel->setTemplate('dgi-module/referendum/email-validation.phtml');
        }
        else {
            $viewModel->setTemplate('dgi-module/email/email-validation.phtml');
        }

        if (!$user) {
            $user = new User();
            $viewModel->setVariables([
                'user' => $user
            ]);
        }
        else {

            $viewModel->setVariables([
                'user' => $user
            ]);

            $htmlMessage = $this->viewRenderer->render($viewModel);

            if ($email) {
                $this->sendEmail($user->getUsrEmail(), $htmlMessage, $this->translator->translate('Complete your Demodyne sign up', 'DGIModule'));
            }

        }

        return $viewModel;
    }


    /**
     * @return ViewModel
     */
    public function newBugAction()
    {
        $user = $this->identity();
        $bugId = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Bug $bug */
        $bug = $this->entityManager->getRepository('DGIModule\Entity\Bug')->findOneBy(['bugId'=>$bugId]);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/new-bug.phtml');

        if (!$bug) {
            $bug = new Bug();
            $bug->setBugDescription('This is a new bug for testing the email template')
                ->setUsr($user)
                ->setBugCreatedDate(new \DateTime());
            $viewModel->setVariables([
                'bug' => $bug
            ]);
        }
        else {

            $viewModel->setVariables([
                'bug' => $bug
            ]);

            $htmlMessage = $this->viewRenderer->render($viewModel);

            if ($email) {
                $this->sendEmail('support@demodyne.org', $htmlMessage, 'Bug submission - '.$bug->getBugTitle());
            }

        }

        return $viewModel;

    }

    /**
     * @return ViewModel
     */
    public function newModerationReportAction()
    {
        $user = $this->identity();
        $reportId = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Report $report */
        $report = $this->entityManager->getRepository('DGIModule\Entity\Report')->findOneBy(['repId'=>$reportId]);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/new-moderation-report.phtml');

        if (!$report) {
            $report = new Report();
            $report->setRepCreatedDate(new \DateTime())
                ->setUsr($user)
                ->setRepDescription('This is the report description... just for testing')
                ->setRepReason('Because we test the action')
                ->setRepType('proposal')
                ->setRepUUID('c365f8b0-1760-11e6-a55a-fa163ed70938')
                ;
            $viewModel->setVariables([
                'report' => $report,
                'text' => 'This is the test text',
                'owner' => $user
            ]);
        }
        else {
            $type = $report->getRepType();
            if ($type == 'comment') {
                /** @var \DGIModule\Entity\Comment $comment */
                $comment = $this->entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID'=>$report->getRepUUID()]);
                $text = $comment->getComText();
                $owner = $comment->getUsr();
            }
            elseif ($type == 'proposal') {
                /** @var \DGIModule\Entity\Proposal $proposal */
                $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$report->getRepUUID()]);
                $text = '<a href="https://www.demodyne.org'.$this->url()->fromRoute('proposal', array('action' => 'view' ,'id'=>$proposal->getPropUUID())).'">'.$proposal->getPropSavedName().'</a>';
                $owner = $proposal->getUsr();
            }
            elseif ($type == 'program') {
                /** @var \DGIModule\Entity\Program $program */
                $program = $this->entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(['progUUID'=>$report->getRepUUID()]);
                $text = '<a href="https://www.demodyne.org'.$this->url()->fromRoute('scenario', array('action' => 'view' ,'id'=>$program->getScnUUID())).'">'.$program->getProgName().'</a>';
                $owner = $program->getUsr();
            }
            elseif ($type == 'inbox') {
                /** @var \DGIModule\Entity\Inbox $inbox */
                $inbox = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID'=>$report->getRepUUID()]);
                $text = $inbox->getIbxText();
                $owner = $inbox->getFromUsr();
            }
            $viewModel->setVariables([
                'report' => $report,
                'text' => $text,
                'owner' => $owner
            ]);

            $htmlMessage = $this->viewRenderer->render($viewModel);

            if ($email) {
                $this->sendEmail('moderation@demodyne.org', $htmlMessage, 'Moderation request on '.$report->getRepType());
            }

        }

        return $viewModel;

    }

    /**
     * @return ViewModel
     */
    public function activateAdministrationAccountAction()
    {

        $userUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        $user = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID'=>$userUUID]);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/activate-administration-account.phtml');

        if (!$user) {
            $user = new User();
            $user->setAdmin(new Administration());
            $viewModel->setVariables([
                'user' => $user
            ]);
        }
        else {
            $viewModel->setVariables([
                'user' => $user
            ]);

            $htmlMessage = $this->viewRenderer->render($viewModel);

            if ($email) {
                $this->sendEmail("validation@demodyne.org", $htmlMessage, $this->translator->translate('Administration account activation request', 'DGIModule'));
            }
        }
        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function administrationAccountActivatedAction()
    {
        $userUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        $user = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID'=>$userUUID]);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/administration-account-activated.phtml');

        if (!$user) {
            $user = new User();
            $user->setAdmin(new Administration());
            $viewModel->setVariables([
                'user' => $user
            ]);
        }
        else {
            $viewModel->setVariables([
                'user' => $user
            ]);

            $this->translator->setLocale($user->getLang()->getLangId());
            $htmlMessage = $this->viewRenderer->render($viewModel);

            if ($email) {
                $this->sendEmail($user->getUsrEmail(), $htmlMessage, $this->translator->translate('DEMODYNE account validated', 'DGIModule'));
            }
        }

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function activationRemainderAction()
    {

        $userUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\User $user */
        $user = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID'=>$userUUID]);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/activation-remainder.phtml');

        if (!$user) {
            $user = new User();
            $viewModel->setVariables([
                'user' => $user
            ]);
        }
        else {
            $viewModel->setVariables([
                'user' => $user
            ]);

            $this->translator->setLocale($user->getLang()->getLangId());
            $htmlMessage = $this->viewRenderer->render($viewModel);

            if ($email) {
                $this->sendEmail($user->getUsrEmail(), $htmlMessage, $this->translator->translate('Activation of your Demodyne account', 'DGIModule'));
            }
        }

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function changePasswordAction()
    {

        $userUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\User $user */
        $user = $this->entityManager->getRepository('DGIModule\Entity\User')->findOneBy(['usrUUID'=>$userUUID]);

        $password = $this->params()->fromRoute('msg', 'no password');

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/change-password.phtml');
        $viewModel->setVariable('password', $password);

        if (!$user) {
            $user = new User();
            $viewModel->setVariables([
                'user' => $user
            ]);
        }
        else {
            $viewModel->setVariables([
                'user' => $user
            ]);

            $htmlMessage = $this->viewRenderer->render($viewModel);

            if ($email) {
                $this->sendEmail($user->getUsrEmail(), $htmlMessage, $this->translator->translate('Your password has been changed!', 'DGIModule'));
            }
        }

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function newCommentAction()
    {

        $commentUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Comment $comment */
        $comment = $this->entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID'=>$commentUUID]);

        if (!$comment) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/new-comment.phtml');

        $subject = '';

        /** @var \DGIModule\Entity\User $to */
        if ($comment->getProp()) {
            $to = $comment->getProp()->getUsr();
            $this->translator->setLocale($to->getLang()->getLangId());
            $subject = sprintf($this->translator->translate('New comment on Proposal "%s"', 'DGIModule'), $comment->getProp()->getPropName());
        }
        elseif ($comment->getProgram()) {
            $to = $comment->getProgram()->getUsr();
            $this->translator->setLocale($to->getLang()->getLangId());
            $subject = sprintf($this->translator->translate('New comment on Program "%s"', 'DGIModule'), $comment->getProgram()->getProgName());
        }
        elseif ($comment->getArticle()) {
            $to = $comment->getArticle()->getUsr();
            $this->translator->setLocale($to->getLang()->getLangId());
            $subject = sprintf($this->translator->translate('New comment on Article "%s"', 'DGIModule'), $comment->getArticle()->getArticleTitle());
        }

        $viewModel->setVariables([
            'comment' => $comment
        ]);

        $htmlMessage = $this->viewRenderer->render($viewModel);

        if ($email) {
            $this->sendEmail($to->getUsrEmail(), $htmlMessage, $subject);
        }

        return $viewModel;
    }


    /**
     * When the status of a proposal changes, it sends instant emails to all concerned users
     *
     * @return ViewModel
     */
    public function proposalStatusChangedAction()
    {

        $propUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Proposal $proposal */
        $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$propUUID]);

        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        $alert = [];

        // proposal owner
        $user = $proposal->getUsr();
        if ($user->getDigest()->getDigestAlertStatus()==$this->config['demodyne']['email']['alert']['instant']) {
            $alert[$user->getUsrEmail()] = $user;
        }

        // users that have this proposal as favorites
        foreach ($proposal->getUsers() as $user) {
            if ($user->getDigest()->getDigestAlertStatus()==$this->config['demodyne']['email']['alert']['instant']) {
                $alert[$user->getUsrEmail()] = $user;
            }
        }

        // users which have this proposal in their program
        foreach ($proposal->getPrograms() as $program) {
            $user = $program->getUsr();
            if ($user->getDigest()->getDigestAlertStatus()==$this->config['demodyne']['email']['alert']['instant']) {
                $alert[$user->getUsrEmail()] = $user;
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/proposal-status-changed.phtml');

        $emails = [];

        if ($email) {

            if (count($alert)) {

                foreach ($alert as $email => $user) {
                    $this->translator->setLocale($user->getLang()->getLangId());
                    $viewModel->setVariables([
                        'proposal' => $proposal,
                        'toUser' => $user
                    ]);
                    $htmlMessage = $this->viewRenderer->render($viewModel);
                    $this->sendEmail($email, $htmlMessage, $this->translator->translate('Proposal status changed on Demodyne', 'DGIModule'));
                    $emails[] = $user->getUsrName();
                }
            }
        }
        else {

            $user = $this->identity();
            $viewModel->setVariables([
                'proposal' => $proposal,
                'toUser' => $user
            ]);

            return $viewModel;
        }
        return new JsonModel($emails);
    }

    /**
     * When the debate period of a proposal has been prolonged, it sends instant emails to all concerned users
     *
     * @return ViewModel
     */
    public function proposalDebatePhaseProlongedAction()
    {

        $propUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Proposal $proposal */
        $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$propUUID]);

        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        $alert = [];

        // proposal owner
        $user = $proposal->getUsr();
        if ($user->getDigest()->getDigestAlertStatus()==$this->config['demodyne']['email']['alert']['instant']) {
            $alert[$user->getUsrEmail()] = $user;
        }

        // users that have this proposal as favorites
        foreach ($proposal->getUsers() as $user) {
            if ($user->getDigest()->getDigestAlertStatus()==$this->config['demodyne']['email']['alert']['instant']) {
                $alert[$user->getUsrEmail()] = $user;
            }
        }

        // users which have this proposal in their program
        foreach ($proposal->getPrograms() as $program) {
            $user = $program->getUsr();
            if ($user->getDigest()->getDigestAlertStatus()==$this->config['demodyne']['email']['alert']['instant']) {
                $alert[$user->getUsrEmail()] = $user;
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/proposal-debate-phase-prolonged.phtml');

        $emails = [];

        if ($email) {

            if (count($alert)) {

                foreach ($alert as $email => $user) {
                    $this->translator->setLocale($user->getLang()->getLangId());
                    $viewModel->setVariables([
                        'proposal' => $proposal,
                        'toUser' => $user
                    ]);
                    $htmlMessage = $this->viewRenderer->render($viewModel);
                    $this->sendEmail($email, $htmlMessage, $this->translator->translate('Proposal status changed on Demodyne', 'DGIModule'));
                    $emails[] = $user->getUsrName();
                }
            }
        }
        else {

            /** @var \DGIModule\Entity\User $user */
            $user = $this->identity();
            if (!$user || !$user->isDemodyneAdmin()) {
                return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
            }
            $viewModel->setVariables([
                'proposal' => $proposal,
                'toUser' => $user
            ]);

            return $viewModel;
        }
        return new JsonModel($emails);
    }

    /**
     * @todo add security
     *
     * When the status of a proposal changes, it sends instant emails to all concerned users
     *
     * @return ViewModel
     */
    public function proposalHasBeenModifiedAction()
    {

        $propUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Proposal $proposal */
        $proposal = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>$propUUID]);

        if (!$proposal) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        $alert = [];

        // proposal owner
        $user = $proposal->getUsr();
        $alert[$user->getUsrEmail()] = $user;

        // users that have this proposal as favorites
        foreach ($proposal->getUsers() as $user) {
            $alert[$user->getUsrEmail()] = $user;
        }

        // users which have this proposal in their program
        foreach ($proposal->getPrograms() as $program) {
            $user = $program->getUsr();
            $alert[$user->getUsrEmail()] = $user;
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/proposal-has-been-modified.phtml');

        $emails = [];

        if ($email) {

            if (count($alert)) {

                foreach ($alert as $email => $user) {
                    $this->translator->setLocale($user->getLang()->getLangId());
                    $viewModel->setVariables([
                        'proposal' => $proposal,
                        'toUser' => $user
                    ]);
                    $htmlMessage = $this->viewRenderer->render($viewModel);
                    $this->sendEmail($email, $htmlMessage, $this->translator->translate('Proposal status changed on Demodyne', 'DGIModule'));
                    $emails[] = $user->getUsrName();
                }
            }
        }
        else {

            $user = $this->identity();
            $viewModel->setVariables([
                'proposal' => $proposal,
                'toUser' => $user
            ]);

            return $viewModel;
        }
        return new JsonModel($emails);
    }

    /**
     * @return ViewModel
     */
    public function newNewsletterAction()
    {
        $msgUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Inbox $inboxMessage */
        $inboxMessage = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID'=>$msgUUID]);

        if (!$inboxMessage) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        /** @var \DGIModule\Entity\Newsletter $newsletter */
        $newsletter = $inboxMessage->getNewsletter();

        if (!$newsletter) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/new-newsletter.phtml');

        $this->translator->setLocale($inboxMessage->getToUsr()->getLang()->getLangId());
        $subject = sprintf($this->translator->translate('Newsletter from %s', 'DGIModule'), $newsletter->getAdmin()->getAdminName());

        $viewModel->setVariables([
            'inboxMessage' => $inboxMessage,
            'toUser' => $inboxMessage->getToUsr()
        ]);

        $htmlMessage = $this->viewRenderer->render($viewModel);

        if ($email) {
            $this->sendEmail($inboxMessage->getToUsr()->getUsrEmail(), $htmlMessage, $subject);
        }
        else {
            return $viewModel;
        }

        return $viewModel;
    }


    /**
     * @return ViewModel
     */
    public function newPrivateMessageAction()
    {

        $msgUUID = $this->params()->fromRoute('id', null);
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        /** @var \DGIModule\Entity\Inbox $inboxMessage */
        $inboxMessage = $this->entityManager->getRepository('DGIModule\Entity\Inbox')->findOneBy(['ibxUUID'=>$msgUUID]);

        if (!$inboxMessage) {
            return $this->forward()->dispatch('DGIModule\Controller\Error', array('action' => 'access-denied'));
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/new-private-message.phtml');

        $this->translator->setLocale($inboxMessage->getToUsr()->getLang()->getLangId());

        $viewModel->setVariables([
            'inboxMessage' => $inboxMessage
        ]);

        $htmlMessage = $this->viewRenderer->render($viewModel);

        if ($email) {
            $this->sendEmail($inboxMessage->getToUsr()->getUsrEmail(), $htmlMessage, sprintf($this->translator->translate('New private message from %s', 'DGIModule'), $inboxMessage->getFromUsr()->getUsrName()));
        }

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function adminDigestAction()
    {

        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/admin-digest.phtml');

        $now = new \DateTime();
        $to = new \DateTime($now->format('Y-m-d').' 00:00');
        $from = clone $to;
        $from->modify( '-1 day' );

        $usersCount = $this->entityManager->getRepository('DGIModule\Entity\User')->countPeriodUsers($from, $to);
        $proposalCount = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countPeriodProposals($from, $to);
        $measuresCount = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countPeriodProposals($from, $to, true);
        $eventsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countPeriodEvents($from, $to, false);
        $sessionsCount = $this->entityManager->getRepository('DGIModule\Entity\Event')->countPeriodEvents($from, $to, true);
        $viewModel->setVariables([
            'proposals' => $proposalCount,
            'measures' =>$measuresCount,
            'users' => $usersCount,
            'events' => $eventsCount,
            'sessions' => $sessionsCount,
            'from' => $from,
            'to' => $to
        ]);

        if ($email) {
            $htmlMessage = $this->viewRenderer->render($viewModel);

            $admins = $this->entityManager->getRepository('DGIModule\Entity\User')->findBy(array('usrlId' => $this->config['demodyne']['account']['type']['admin']));
            /** @var \DGIModule\Entity\User $admin */
            foreach ($admins as $admin) {
                $this->sendEmail($admin->getUsrEmail(), $htmlMessage, sprintf($this->translator->translate('Admin digest from %s to %s', 'DGIModule'), $from->format('d/m/Y h:i'), $to->format('d/m/Y h:i')));
            }
        }

        return new JsonModel([
            'fromDate' => $from->format('d/m/Y h:i'),
            'toDate' => $to->format('d/m/Y h:i'),
            'newUsers' => $usersCount,
            'newProposals' => $proposalCount,
            'newMeasures' => $measuresCount,
            'newEvents' => $eventsCount,
            'newSessions' => $sessionsCount
        ]);
    }

    /**
     * @return ViewModel
     */
    public function userAlertAction()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip!=$this->config['demodyne']['cron']['ip']) {
            printf('You don\'t have access to this ressource');
            exit();
        }

        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/user-alert.phtml');

        $freq = $this->config['demodyne']['email']['alert'];
        $to= new \DateTime();
        $from = clone $to;
        $from->modify('-1 week');

        $alert = [];
        $updateDigest = [];

        // get users with unread messages last week and last day
        $usersWithMessages = $this->entityManager->getRepository('DGIModule\Entity\User')->getUsersWithUnreadMessagesByPeriod();
        /** @var \DGIModule\Entity\User $user */
        foreach ($usersWithMessages as $userItem) {
            $user = $userItem[0];
            $weeklyMessages = (int)$userItem['weeklyMessages'];
            $dailyMessages = (int)$userItem['dailyMessages'];
            /** @var \DGIModule\Entity\UserDigest $digest */
            $digest = $user->getDigest();
            if ($digest->getDigestAlertPrivate()==$freq['weekly'] && $digest->getDigestAlertDate() <= $from && $weeklyMessages) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['privateMessages'] = $weeklyMessages;
                $alert[$user->getUsrEmail()]['privateMessagesWeek'] = 1;
                $updateDigest[$digest->getDigestId()] = $digest;
            }
            elseif ($digest->getDigestAlertPrivate()==$freq['daily'] && $dailyMessages) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['privateMessages'] = $dailyMessages;
                $alert[$user->getUsrEmail()]['privateMessagesWeek'] = 0;
            }
        }

        /** @todo new comments for programs */
        $proposalsWithComments = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->getProposalWithCommentsByPeriod();
        /** @var \DGIModule\Entity\Proposal $proposal */
        foreach ($proposalsWithComments as $proposalItem) {
            $proposal = $proposalItem[0];
            $weeklyComments = (int)$proposalItem['weeklyComments'];
            $dailyComments = (int)$proposalItem['dailyComments'];
            // check proposal owner
            $user = $proposal->getUsr();
            $digest = $user->getDigest();
            if ($digest->getDigestAlertComments()==$freq['weekly'] && $digest->getDigestAlertDate() <= $from && $weeklyComments) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['comments'] = $weeklyComments;
                $updateDigest[$digest->getDigestId()] = $digest;
            }
            elseif ($digest->getDigestAlertComments()==$freq['daily'] && $dailyComments) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['comments'] = $dailyComments;
            }
            // proposal as favorite for user
            foreach ($proposal->getUsers() as $user) {
                if (isset($alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()])) continue;
                $digest = $user->getDigest();
                if ($digest->getDigestAlertComments()==$freq['weekly'] && $digest->getDigestAlertDate() <= $from && $weeklyComments) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['comments'] = $weeklyComments;
                    $updateDigest[$digest->getDigestId()] = $digest;
                }
                elseif ($digest->getDigestAlertComments()==$freq['daily'] && $dailyComments) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['comments'] = $dailyComments;
                }
            }
            // proposal in program of user
            foreach ($proposal->getPrograms() as $program) {
                if (isset($alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()])) continue;
                $user = $program->getUsr();
                $digest = $user->getDigest();
                if ($digest->getDigestAlertComments()==$freq['weekly'] && $digest->getDigestAlertDate() <= $from && $weeklyComments) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['comments'] = $weeklyComments;
                    $updateDigest[$digest->getDigestId()] = $digest;
                }
                elseif ($digest->getDigestAlertComments()==$freq['daily'] && $dailyComments) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['comments'] = $dailyComments;
                }
            }

        }

        /// @todo Add proposals with already status in last day or week
        /// @todo Add proposals with prolong debate in last day or week
        /// @todo Add proposals with updates (name, description, category, etc.) in last day or week

        $proposalsWithStatusChange = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->getProposalWithStatusChangeByPeriod();
        /** @var \DGIModule\Entity\Proposal $proposal */
        foreach ($proposalsWithStatusChange as $proposalItem) {
            $proposal = $proposalItem[0];
            $statusChange = (int)$proposalItem['statusChange'];
            // proposal owner
            $user = $proposal->getUsr();
            $digest = $user->getDigest();
            if (($digest->getDigestAlertStatus()&2==2 && $statusChange==5) ||
                ($digest->getDigestAlertStatus()&1==1 && $statusChange==2)) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['status'] = $statusChange;
            }
            // users that have this proposal as favorites
            foreach ($proposal->getUsers() as $user) {
                $digest = $user->getDigest();
                if (($digest->getDigestAlertStatus()&2==2 && $statusChange==5) ||
                    ($digest->getDigestAlertStatus()&1==1 && $statusChange==2)) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['status'] = $statusChange;
                }
            }
            // users which have this proposal in their program
            foreach ($proposal->getPrograms() as $program) {
                $user = $program->getUsr();
                $digest = $user->getDigest();
                if (($digest->getDigestAlertStatus()&2==2 && $statusChange==5) ||
                    ($digest->getDigestAlertStatus()&1==1 && $statusChange==2)) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['proposal'] = $proposal;
                    $alert[$user->getUsrEmail()]['proposals'][$proposal->getPropUUID()]['status'] = $statusChange;
                }
            }

        }

        $programsWithComments = $this->entityManager->getRepository('DGIModule\Entity\Program')->getProgramsWithCommentsByPeriod();
        /** @var \DGIModule\Entity\Proposal $proposal */
        foreach ($programsWithComments as $programItem) {
            $program = $programItem[0];
            $weeklyComments = (int)$programItem['weeklyComments'];
            $dailyComments = (int)$programItem['dailyComments'];
            // check program owner
            $user = $program->getUsr();
            $digest = $user->getDigest();
            if ($digest->getDigestAlertComments()==$freq['weekly'] && $digest->getDigestAlertDate() <= $from && $weeklyComments) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['programs'][$proposal->getPropUUID()]['program'] = $program;
                $alert[$user->getUsrEmail()]['programs'][$proposal->getPropUUID()]['comments'] = $weeklyComments;
                $updateDigest[$digest->getDigestId()] = $digest;
            }
            elseif ($digest->getDigestAlertComments()==$freq['daily'] && $dailyComments) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['programs'][$proposal->getPropUUID()]['program'] = $program;
                $alert[$user->getUsrEmail()]['programs'][$proposal->getPropUUID()]['comments'] = $dailyComments;
            }
        }

        $eventsWithStartDays = $this->entityManager->getRepository('DGIModule\Entity\Event')->getEventsWithStartChangeByPeriod();
        /** @var \DGIModule\Entity\Event $event*/
        foreach ($eventsWithStartDays as $eventItem) {
            $event = $eventItem[0];
            $startDays = (int)$eventItem['startDays'];
            // event owner
            $user = $event->getUsr();
            $digest = $user->getDigest();
            if (($digest->getDigestAlertEvent()&2==2 && $startDays==5) ||
                ($digest->getDigestAlertEvent()&1==1 && $startDays==2)) {
                $alert[$user->getUsrEmail()]['user'] = $user;
                $alert[$user->getUsrEmail()]['events'][$event->getEventId()]['event'] = $event;
                $alert[$user->getUsrEmail()]['events'][$event->getEventId()]['start'] = $startDays;
            }
            // users that attend the event
            foreach ($event->getAttendees() as $user) {
                $digest = $user->getDigest();
                if (($digest->getDigestAlertEvent()&2==2 && $startDays==5) ||
                    ($digest->getDigestAlertEvent()&1==1 && $startDays==2)) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['events'][$event->getEventId()]['event'] = $event;
                    $alert[$user->getUsrEmail()]['events'][$event->getEventId()]['start'] = $startDays;
                }
            }
            // users that are invited to the event
            foreach ($event->getInvitations() as $user) {
                $digest = $user->getDigest();
                if (($digest->getDigestAlertEvent()&2==2 && $startDays==5) ||
                    ($digest->getDigestAlertEvent()&1==1 && $startDays==2)) {
                    $alert[$user->getUsrEmail()]['user'] = $user;
                    $alert[$user->getUsrEmail()]['events'][$event->getEventId()]['event'] = $event;
                    $alert[$user->getUsrEmail()]['events'][$event->getEventId()]['start'] = $startDays;
                }
            }

        }

        if ($email) {
            if (count($updateDigest)) {
                foreach ($updateDigest as $digest) {
                    $digest->setDigestAlertDate($to);
                    $this->entityManager->merge($digest);
                }
                $this->entityManager->flush();
            }

            if (count($alert)) {
                foreach ($alert as $email => $alertItem) {
                    if (isset($alertItem['proposals'])) {
                        usort($alertItem['proposals'], array('\DGIModule\Controller\EmailController', 'compareProposalsByName'));
                    }
                    if (isset($alertItem['events'])) {
                        usort($alertItem['events'], array('\DGIModule\Controller\EmailController', 'compareEventsByName'));
                    }
                    $user = $alertItem['user'];
                    $this->translator->setLocale($user->getLang()->getLangId());
                    $viewModel->setVariables([
                        'alert' => $alertItem
                    ]);
                    $htmlMessage = $this->viewRenderer->render($viewModel);

                    $this->sendEmail($email, $htmlMessage, $this->translator->translate('Demodyne Alert', 'DGIModule'));
                    $alertItem['user'] = $user->getUsrName();
                }
            }
        }
        else {
            if (count($alert)) {
                $alertItem = array_pop($alert);
            }
            else {
                $user = $this->identity();
                $alertItem = [
                    'user' => $user,
                    'privateMessages' => 999999,
                    'privateMessagesWeek' => 1,
                    'proposals' => [
                        0 => [
                            'proposal' => $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>'c51d09ef-941e-48cf-a1b7-194d1161c4d1']),
                            'comments' => 1,
                            'status' => 3
                        ],
                        1 => [
                            'proposal' => $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>'21b87218-d646-11e5-b34d-fa163ed70938']),
                            'comments' => 5
                        ],
                        2 => [
                            'proposal' => $this->entityManager->getRepository('DGIModule\Entity\Proposal')->findOneBy(['propUUID'=>'ac104dc5-fa10-4cd1-9b69-56100058b5f8']),
                            'status' => 3
                        ]
                    ],
                    'programs' => [
                        0 => [
                            'program' => $this->entityManager->getRepository('DGIModule\Entity\Program')->findOneBy(['progUUID'=>'c809b146-2d57-11e6-9880-fa163ed70938']),
                            'comments' => 1,
                        ],
                    ],
                    'events' => [
                        0 => [
                            'event' => $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID'=>'2cb7ba3c-c4d3-403c-81bd-4c87fbb54eb8']),
                            'start' => 9
                        ],
                        1 => [
                            'event' => $this->entityManager->getRepository('DGIModule\Entity\Event')->findOneBy(['eventUUID'=>'4139222c-4bfb-4c96-95fe-054b4a045ab8']),
                            'start' => 5
                        ],
                    ]
                ];
            }
            if (isset($alertItem['proposals'])) {
                usort($alertItem['proposals'], array('\DGIModule\Controller\EmailController', 'compareProposalsByName'));
            }
            if (isset($alertItem['events'])) {
                usort($alertItem['events'], array('\DGIModule\Controller\EmailController', 'compareEventsByName'));
            }
            $viewModel->setVariables([
                'alert' => $alertItem,
            ]);

            return $viewModel;
        }

        return new JsonModel($alert);
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    private static function compareProposalsByName($a, $b) {
        return strcmp(strtolower($a['proposal']->getPropName()), strtolower($b['proposal']->getPropName()));
    }

    private static function compareEventsByName($a, $b) {
        return strcmp(strtolower($a['event']->getEventName()), strtolower($b['event']->getEventName()));
    }


    /**
     * @return ViewModel
     */
    public function userDigestAction()
    {
        $user = $this->identity();
        $email = filter_var($this->params()->fromRoute('email', false), FILTER_VALIDATE_BOOLEAN);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('dgi-module/email/user-digest.phtml');

        $stateChangeViewModel = new ViewModel();
        $stateChangeViewModel->setTemplate('dgi-module/email/alert-state-change.phtml');

        $levels = $this->config['demodyne']['level'];
        $freq = $this->config['demodyne']['email']['digest'];
        $to= new \DateTime();


        if ($email) {

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            if ($ip!=$this->config['demodyne']['cron']['ip']) {
                printf('You don\'t have access to this ressource');
                exit();
            }

            $users = $this->entityManager->getRepository('DGIModule\Entity\User')->findBy(['usrActive'=>1, 'usrDeletedDate'=>null, 'usrlId'=> $this->config['demodyne']['account']['type']['admin']]);

            $updateDigest = false;

            /** @var \DGIModule\Entity\User $user */
            foreach ($users as $user) {

                if (!$user->getCity()) continue;

                /** @var \DGIModule\Entity\UserDigest $digest */
                $digest = $user->getDigest();
                if ($digest->getDigestFrequency()!=$freq['never']) {
                    $from = clone $to;
                    switch ($digest->getDigestFrequency()) {
                        case $freq['daily']:
                            $from->modify('-1 day');
                            break;
                        case $freq['weekly']:
                            $from->modify('-1 week');
                            break;
                        case $freq['monthly']:
                            $from->modify('-1 month');
                            break;
                        default:
                            $from->modify('-1 week');
                            break;
                    }

                    if ($digest->getDigestSentDate()<=$from) {

                        $from = $digest->getDigestSentDate();

                        $articles = $this->entityManager->getRepository('DGIModule\Entity\Article')->getPeriodArticles($user, $from, $to);
                        $count = count($articles);
                        $viewModel->setVariables([
                            'articles' => $articles,
                            'from' => $from,
                            'to' => $to,
                            'user' => $user
                        ]);

                        if ($digest->getDigestPropProg()) {
                            $proposals = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countUserPeriodProposals($user, $from, $to, $levels, false);
                            $measures = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countUserPeriodProposals($user, $from, $to, $levels, true);
                            $programs = $this->entityManager->getRepository('DGIModule\Entity\Program')->countUserPeriodPrograms($user, $from, $to, $levels);
                            $count += $proposals['total'] + $measures['total'] + $programs['total'];
                            $viewModel->setVariables([
                                'proposals' => $proposals,
                                'measures' => $measures,
                                'programs' => $programs,
                            ]);
                        }

                        if ($digest->getDigestEvent()) {
                            $events = $this->entityManager->getRepository('DGIModule\Entity\Event')->countUserPeriodEvents($user, $from, $to, $levels, false);
                            $sessions = $this->entityManager->getRepository('DGIModule\Entity\Event')->countUserPeriodEvents($user, $from, $to, $levels, true);
                            $count += $events['total'] + $sessions['total'];
                            $viewModel->setVariables([
                                'events' => $events,
                                'sessions' => $sessions,
                            ]);
                        }

                        if ($count) {

                            $htmlMessage = $this->viewRenderer->render($viewModel);

                            $this->sendEmail($user->getUsrEmail(), $htmlMessage, $this->translator->translate('Demodyne Digest', 'DGIModule'));

                            $digest->setDigestSentDate($to);
                            $this->entityManager->merge($digest);
                            $updateDigest = true;

                            echo $user->getUsrName() . ' ' . array_search($digest->getDigestFrequency(), $freq) . '\n';
                        }
                    }
                }
            }
            if ($updateDigest) {
                $this->entityManager->flush();
            }
            exit();
        }
        else {

            if (!$user) {
                $user = new User();
                $saintLouis = $this->entityManager->getRepository('DGIModule\Entity\City')->findOneBy(['cityId' => 26241]);
                $user->setCity($saintLouis);
            }

            $from = clone $to;
            $days = 180;
            $from->modify( sprintf('-%d day', $days) );

            $articles = $this->entityManager->getRepository('DGIModule\Entity\Article')->getPeriodArticles($user, $from, $to);
            $proposals = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countUserPeriodProposals($user, $from, $to, $levels, false);
            $measures = $this->entityManager->getRepository('DGIModule\Entity\Proposal')->countUserPeriodProposals($user, $from, $to, $levels, true);
            $programs = $this->entityManager->getRepository('DGIModule\Entity\Program')->countUserPeriodPrograms($user, $from, $to, $levels);
            $events = $this->entityManager->getRepository('DGIModule\Entity\Event')->countUserPeriodEvents($user, $from, $to, $levels, false);
            $sessions = $this->entityManager->getRepository('DGIModule\Entity\Event')->countUserPeriodEvents($user, $from, $to, $levels, true);

            $viewModel->setVariables([
                'articles' => $articles,
                'proposals' => $proposals,
                'measures' =>$measures,
                'programs' => $programs,
                'events' => $events,
                'sessions' => $sessions,
                'from' => $from,
                'to' => $to,
                'user' => $user
            ]);

            return $viewModel;
        }





    }


    public function sendEmail($email, $htmlMessage, $subject)
    {

        $this->layout('layout/email');

        $message = new Message();

        $viewLayout = new ViewModel(array('content' => $htmlMessage));
        $viewLayout->setTemplate('layout/email'); // set in module.config.php

        $htmlMessage = $this->viewRenderer->render($viewLayout);

        $html = new MimePart($htmlMessage);
        $html->type = "text/html; charset=UTF-8";

        $textMessage = new Html2Text($htmlMessage);
        $textMessage = $textMessage->getText();

        $text = new MimePart($textMessage);
        $text->type = "text/plain; charset=UTF-8";

        $body = new MimeMessage();
        $body->setParts([$text, $html]);

        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        $request->getServer();
        $message->addTo($email)
            ->addFrom('contact@demodyne.org')
            ->setSubject($subject)
            ->setBody($body);
        $message->getHeaders()->get('content-type')->setType('multipart/alternative');

        $this->mailTransport->send($message);

    }
}