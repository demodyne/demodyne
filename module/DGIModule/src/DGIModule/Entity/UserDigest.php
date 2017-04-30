<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Import/Entity/DgiUserDigest
 *
 * @ORM\Table(name="dgi_user_digest")
 * @ORM\Entity
 */
class UserDigest
{
    /**
     * @var integer
     *
     * @ORM\Column(name="digest_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $digestId;


    /**
     * @var integer
     *
     * @ORM\Column(name="digest_frequency", type="integer", nullable=true)
     */
    private $digestFrequency = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="digest_highlights", type="integer", nullable=true)
     */
    private $digestHighlights = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="digest_prop_prog", type="integer", nullable=true)
     */
    private $digestPropProg = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="digest_academy", type="integer", nullable=true)
     */
    private $digestAcademy = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="digest_event", type="integer", nullable=true)
     */
    private $digestEvent = 1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="digest_sent_date", type="utcdatetime", nullable=true)
     */
    private $digestSentDate;


    /**
     * @var integer
     *
     * @ORM\Column(name="digest_alert_comments", type="integer", nullable=true)
     */
    private $digestAlertComments = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="digest_alert_updates", type="integer", nullable=true)
     */
    private $digestAlertUpdates = 1;



    /**
     * @var integer
     *
     * @ORM\Column(name="digest_alert_status", type="integer", nullable=true)
     */
    private $digestAlertStatus = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="digest_alert_event", type="integer", nullable=true)
     */
    private $digestAlertEvent = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="digest_alert_private", type="integer", nullable=true)
     */
    private $digestAlertPrivate = 2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="digest_alert_date", type="utcdatetime", nullable=true)
     */
    private $digestAlertDate;

    public function __construct()
    {
        $this->digestSentDate = new \DateTime();
        $this->digestAlertDate = new \DateTime();

    }

    /**
     * @return int
     */
    public function getDigestId()
    {
        return $this->digestId;
    }


    /**
     * @return int
     */
    public function getDigestFrequency()
    {
        return $this->digestFrequency;
    }

    /**
     * @param int $digestFrequency
     * @return UserDigest
     */
    public function setDigestFrequency($digestFrequency)
    {
        $this->digestFrequency = $digestFrequency;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestHighligts()
    {
        return $this->digestHighlights;
    }

    /**
     * @param int $digestHighlights
     * @return UserDigest
     */
    public function setDigestHighligts($digestHighlights)
    {
        $this->digestHighlights = $digestHighlights;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestPropProg()
    {
        return $this->digestPropProg;
    }

    /**
     * @param int $digestPropProg
     * @return UserDigest
     */
    public function setDigestPropProg($digestPropProg)
    {
        $this->digestPropProg = $digestPropProg;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestAcademy()
    {
        return $this->digestAcademy;
    }

    /**
     * @param int $digestAcademy
     * @return UserDigest
     */
    public function setDigestAcademy($digestAcademy)
    {
        $this->digestAcademy = $digestAcademy;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestEvent()
    {
        return $this->digestEvent;
    }

    /**
     * @param int $digestEvent
     * @return UserDigest
     */
    public function setDigestEvent($digestEvent)
    {
        $this->digestEvent = $digestEvent;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDigestSentDate()
    {
        return $this->digestSentDate;
    }

    /**
     * @param \DateTime $digestSentDate
     * @return UserDigest
     */
    public function setDigestSentDate($digestSentDate)
    {
        $this->digestSentDate = $digestSentDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestAlertComments()
    {
        return $this->digestAlertComments;
    }

    /**
     * @param int $digestAlertComments
     * @return UserDigest
     */
    public function setDigestAlertComments($digestAlertComments)
    {
        $this->digestAlertComments = $digestAlertComments;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestAlertStatus()
    {
        return $this->digestAlertStatus;
    }

    /**
     * @param int $digestAlertStatus
     * @return UserDigest
     */
    public function setDigestAlertStatus($digestAlertStatus)
    {
        $this->digestAlertStatus = $digestAlertStatus;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestAlertEvent()
    {
        return $this->digestAlertEvent;
    }

    /**
     * @param int $digestAlertEvent
     * @return UserDigest
     */
    public function setDigestAlertEvent($digestAlertEvent)
    {
        $this->digestAlertEvent = $digestAlertEvent;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestAlertPrivate()
    {
        return $this->digestAlertPrivate;
    }

    /**
     * @param int $digestAlertPrivate
     * @return UserDigest
     */
    public function setDigestAlertPrivate($digestAlertPrivate)
    {
        $this->digestAlertPrivate = $digestAlertPrivate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDigestAlertDate()
    {
        return $this->digestAlertDate;
    }

    /**
     * @param \DateTime $digestAlertDate
     * @return UserDigest
     */
    public function setDigestAlertDate($digestAlertDate)
    {
        $this->digestAlertDate = $digestAlertDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getDigestAlertUpdates()
    {
        return $this->digestAlertUpdates;
    }

    /**
     * @param int $digestAlertUpdates
     * @return UserDigest
     */
    public function setDigestAlertUpdates($digestAlertUpdates)
    {
        $this->digestAlertUpdates = $digestAlertUpdates;
        return $this;
    }


}

