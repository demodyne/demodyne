<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */
 
namespace DGIModule\Entity;

use DGIModule\Entity\City;
use DGIModule\Entity\Proposal;
use DGIModule\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="dgi_news", indexes={@ORM\Index(name="city_id_idx", columns={"city_id"}), @ORM\Index(name="scn_id_idx", columns={"scn_id"}), @ORM\Index(name="prop_id_idx", columns={"prop_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\NewsRepository")
 */
class News
{
    /**
     * @var integer
     *
     * @ORM\Column(name="news_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $newsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="news_type", type="integer", nullable=false)
     */
    private $newsType;

    /**
     * @var integer
     *
     * @ORM\Column(name="news_level", type="integer", nullable=false)
     */
    private $newsLevel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_created_date", type="utcdatetime", nullable=true)
     */
    private $newsCreatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="news_UUID", type="uuid", length=36, nullable=true)
     */
    private $newsUUID;

    /**
     * @var Proposal
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Proposal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop;

    /**
     * @var \DGIModule\Entity\Event
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     * })
     */
    private $event;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="city_id")
     * })
     */
    private $city;

        /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;


    /**
     * Get newsId
     *
     * @return integer 
     */
    public function getNewsId()
    {
        return $this->newsId;
    }

    /**
     * Set newsType
     *
     * @param integer $newsType
     * @return News
     */
    public function setNewsType($newsType)
    {
        $this->newsType = $newsType;

        return $this;
    }

    /**
     * Get newsType
     *
     * @return integer 
     */
    public function getNewsType()
    {
        return $this->newsType;
    }

    /**
     * Set newsCreatedDate
     *
     * @param \DateTime $newsCreatedDate
     * @return News
     */
    public function setNewsCreatedDate($newsCreatedDate)
    {
        $this->newsCreatedDate = $newsCreatedDate;

        return $this;
    }

    /**
     * Get newsCreatedDate
     *
     * @return \DateTime 
     */
    public function getNewsCreatedDate()
    {
        return $this->newsCreatedDate;
    }

    /**
     * Set newsUUID
     *
     * @param string $newsUUID
     * @return News
     */
    public function setNewsUUID($newsUUID)
    {
        $this->newsUUID = $newsUUID;

        return $this;
    }

    /**
     * Get newsUUID
     *
     * @return string 
     */
    public function getNewsUUID()
    {
        return $this->newsUUID;
    }

    /**
     * Set prop
     *
     * @param Proposal $prop
     * @return News
     */
    public function setProp(Proposal $prop = null)
    {
        $this->prop = $prop;

        return $this;
    }

    /**
     * Get prop
     *
     * @return Proposal
     */
    public function getProp()
    {
        return $this->prop;
    }

    /**
     * Set city
     *
     * @param City $city
     * @return News
     */
    public function setCity(City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    
    /**
     * Set usr
     *
     * @param User $usr
     *
     * @return News
     */
    public function setUsr(User $usr = null)
    {
        $this->usr = $usr;
    
        return $this;
    }
    
    /**
     * Get usr
     *
     * @return User
     */
    public function getUsr()
    {
        return $this->usr;
    }

    /**
     * @param Event $event
     * @return News
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return int
     */
    public function getNewsLevel()
    {
        return $this->newsLevel;
    }

    /**
     * @param int $newsLevel
     * @return News
     */
    public function setNewsLevel($newsLevel)
    {
        $this->newsLevel = $newsLevel;
        return $this;
    }


}
