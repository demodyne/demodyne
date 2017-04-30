<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use DGIModule\Entity\City;
use DGIModule\Entity\Country;
use DGIModule\Entity\Region;
use DGIModule\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Event
 *
 * @ORM\Table(name="dgi_events", indexes={@ORM\Index(name="ev_usr_id_idx", columns={"usr_id"}), @ORM\Index(name="ev_city_id_idx", columns={"city_id"}), @ORM\Index(name="ev_region_id_idx", columns={"region_id"}), @ORM\Index(name="ev_country_id_idx", columns={"country_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\EventRepository")
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventId;

    /**
     * @var string
     *
     * @ORM\Column(name="event_name", type="string", length=200, nullable=false)
     */
    private $eventName;

    /**
     * @var string
     *
     * @ORM\Column(name="event_description", type="text", length=65535, nullable=true)
     */
    private $eventDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="event_link", type="string", length=2000, nullable=true)
     */
    private $eventLink;

    /**
     * @var string
     *
     * @ORM\Column(name="event_image", type="string", length=100, nullable=true)
     */
    private $eventImage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_start_date", type="utcdatetime", nullable=false)
     */
    private $eventStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_end_date", type="utcdatetime", nullable=false)
     */
    private $eventEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_created_date", type="utcdatetime", nullable=true)
     */
    private $eventCreatedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_published_date", type="utcdatetime", nullable=true)
     */
    private $eventPublishedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_canceled_date", type="utcdatetime", nullable=true)
     */
    private $eventCanceledDate;

    /**
     * @var string
     *
     * @ORM\Column(name="event_uuid", type="uuid", length=36, nullable=true)
     */
    private $eventUUID;

    /**
     * @var string
     *
     * @ORM\Column(name="event_location", type="string", length=500, nullable=true)
     */
    private $eventLocation;

    /**
     * @var integer
     *
     * @ORM\Column(name="event_relevant_region", type="integer", nullable=true)
     */
    private $eventRelevantRegion = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="event_relevant_country", type="integer", nullable=true)
     */
    private $eventRelevantCountry = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="event_level", type="integer", nullable=true)
     */
    private $eventLevel = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="event_full_city", type="integer", nullable=true)
     */
    private $eventFullCity = 0;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="event_session", type="boolean", nullable=true)
     */
    private $eventSession = 0;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="event_session_completed", type="boolean", nullable=true)
     */
    private $eventSessionCompleted = 0;

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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     * })
     */
    private $region;

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
     * @var \Doctrine\Common\Collections\Collection|User[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\User", inversedBy="attendantForEvents", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_events_users",
     *  joinColumns={
     *      @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     *  }
     * )
     */
    private $attendees;
    
    /**
     * @var \Doctrine\Common\Collections\Collection|User[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\User", inversedBy="invitationsForEvents", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_events_invitations",
     *  joinColumns={
     *      @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     *  }
     * )
     */
    private $invitations;
    
    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Idea", mappedBy="event") */
    private $ideas;
    
    /**
     * @var \DGIModule\Entity\Chat
     *
     * @ORM\OneToOne(targetEntity="DGIModule\Entity\Chat", cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chat_id", referencedColumnName="chat_id")
     * })
     */
    private $chat;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->ideas = new ArrayCollection();
    }
    
    public function getInvitations()
    {
        return $this->invitations;
    }
    
    public function hasInvitation(User $user)
    {
        return $this->invitations->contains($user);
    }


    /**
     * @param User $user
     */
    public function addInvitation(User $user)
    {
        if ($this->invitations->contains($user)) {
            return;
        }
        $this->invitations->add($user);
    }

    /**
     * @param User $user
     */
    public function removeInvitation(User $user)
    {
        if (!$this->invitations->contains($user)) {
            return;
        }
        $this->invitations->removeElement($user);
    
    }
    
    public function clearInvitations() {
        $this->invitations->clear();
    }
    
    
    public function getAttendees()
    {
        return $this->attendees;
    }
    
    public function hasAttendee(User $user) {
        foreach ($this->attendees as $att)
        	if ($att==$user)
        	    return true;
    	return false;
    }

    /**
     * @param User $user
     */
    public function addAttendee(User $user)
    {
        if ($this->attendees->contains($user)) {
            return;
        }
        $this->attendees->add($user);
    }

    /**
     * @param User $user
     */
    public function removeAttendee(User $user)
    {
        if (!$this->attendees->contains($user)) {
            return;
        }
        $this->attendees->removeElement($user);
    }
    
    public function clearAttendees() {
        $this->attendees->clear();
    }


    /**
     * Get eventId
     *
     * @return integer
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set eventName
     *
     * @param string $eventName
     *
     * @return Event
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set eventDescription
     *
     * @param string $eventDescription
     *
     * @return Event
     */
    public function setEventDescription($eventDescription)
    {
        $this->eventDescription = $eventDescription;

        return $this;
    }

    /**
     * Get eventDescription
     *
     * @return string
     */
    public function getEventDescription()
    {
        return $this->eventDescription;
    }

    /**
     * Set eventLink
     *
     * @param string $eventLink
     *
     * @return Event
     */
    public function setEventLink($eventLink)
    {
        $this->eventLink = $eventLink;

        return $this;
    }

    /**
     * Get eventLink
     *
     * @return string
     */
    public function getEventLink()
    {
        return $this->eventLink;
    }

    /**
     * Set eventImage
     *
     * @param string $eventImage
     *
     * @return Event
     */
    public function setEventImage($eventImage)
    {
        $this->eventImage = $eventImage;

        return $this;
    }

    /**
     * Get eventImage
     *
     * @return string
     */
    public function getEventImage()
    {
        return $this->eventImage;
    }

    /**
     * Set eventStartDate
     *
     * @param \DateTime $eventStartDate
     *
     * @return Event
     */
    public function setEventStartDate($eventStartDate)
    {
        $this->eventStartDate = $eventStartDate;

        return $this;
    }

    /**
     * Get eventStartDate
     *
     * @return \DateTime
     */
    public function getEventStartDate()
    {
        return $this->eventStartDate;
    }

    /**
     * Set eventEndDate
     *
     * @param \DateTime $eventEndDate
     *
     * @return Event
     */
    public function setEventEndDate($eventEndDate)
    {
        $this->eventEndDate = $eventEndDate;

        return $this;
    }

    /**
     * Get eventEndDate
     *
     * @return \DateTime
     */
    public function getEventEndDate()
    {
        return $this->eventEndDate;
    }

    /**
     * Set eventCreatedDate
     *
     * @param \DateTime $eventCreatedDate
     *
     * @return Event
     */
    public function setEventCreatedDate($eventCreatedDate)
    {
        $this->eventCreatedDate = $eventCreatedDate;

        return $this;
    }

    /**
     * Get eventCreatedDate
     *
     * @return \DateTime
     */
    public function getEventCreatedDate()
    {
        return $this->eventCreatedDate;
    }

    /**
     * Set eventPublishedDate
     *
     * @param \DateTime $eventPublishedDate
     *
     * @return Event
     */
    public function setEventPublishedDate($eventPublishedDate)
    {
        $this->eventPublishedDate = $eventPublishedDate;

        return $this;
    }

    /**
     * Get eventPublishedDate
     *
     * @return \DateTime
     */
    public function getEventPublishedDate()
    {
        return $this->eventPublishedDate;
    }

    /**
     * Set eventCancelDate
     *
     * @param \DateTime $eventCanceledDate
     *
     * @return Event
     */
    public function setEventCanceledDate($eventCanceledDate)
    {
        $this->eventCanceledDate = $eventCanceledDate;

        return $this;
    }

    /**
     * Get eventCancelDate
     *
     * @return \DateTime
     */
    public function getEventCanceledDate()
    {
        return $this->eventCanceledDate;
    }

    /**
     * Get eventUUID
     *
     * @return string
     */
    public function getEventUUID()
    {
        return $this->eventUUID;
    }

    /**
     * Set eventLocation
     *
     * @param string $eventLocation
     *
     * @return Event
     */
    public function setEventLocation($eventLocation)
    {
        $this->eventLocation = $eventLocation;

        return $this;
    }

    /**
     * Get eventLocation
     *
     * @return string
     */
    public function getEventLocation()
    {
        return $this->eventLocation;
    }

    /**
     * Set eventRelevantRegion
     *
     * @param integer $eventRelevantRegion
     *
     * @return Event
     */
    public function setEventRelevantRegion($eventRelevantRegion)
    {
        $this->eventRelevantRegion = $eventRelevantRegion;

        return $this;
    }

    /**
     * Get eventRelevantRegion
     *
     * @return integer
     */
    public function getEventRelevantRegion()
    {
        return $this->eventRelevantRegion;
    }

    /**
     * Set eventRelevantCountry
     *
     * @param integer $eventRelevantCountry
     *
     * @return Event
     */
    public function setEventRelevantCountry($eventRelevantCountry)
    {
        $this->eventRelevantCountry = $eventRelevantCountry;

        return $this;
    }

    /**
     * Get eventRelevantCountry
     *
     * @return integer
     */
    public function getEventRelevantCountry()
    {
        return $this->eventRelevantCountry;
    }

    /**
     * Set eventLevel
     *
     * @param integer $eventLevel
     *
     * @return Event
     */
    public function setEventLevel($eventLevel)
    {
        $this->eventLevel = $eventLevel;
    
        return $this;
    }
    
    /**
     * Get eventLevel
     *
     * @return integer
     */
    public function getEventLevel()
    {
        return $this->eventLevel;
    }
    
    /**
     * Set eventFullCity
     *
     * @param integer $eventFullCity
     *
     * @return Event
     */
    public function setEventFullCity($eventFullCity)
    {
        $this->eventFullCity = $eventFullCity;
    
        return $this;
    }
    
    /**
     * Get eventFullCity
     *
     * @return integer
     */
    public function getEventFullCity()
    {
        return $this->eventFullCity;
    }
    
    public function isSession() {
        return $this->eventSession==1;
    }
    
    /**
     * @return boolean $eventSession
     */
    public function getEventSession()
    {
        return $this->eventSession;
    }
    
    /**
     * @param boolean $eventSession
     * @return Event
     */
    public function setEventSession($eventSession)
    {
        $this->eventSession = $eventSession;
        return $this;
    }
    
    /**
     * @return boolean $eventSessionCompleted
     */
    public function getEventSessionCompleted()
    {
        return $this->eventSessionCompleted;
    }
    
    /**
     * @param boolean $eventSessionCompleted
     * @return Event
     */
    public function setEventSessionCompleted($eventSessionCompleted)
    {
        $this->eventSessionCompleted = $eventSessionCompleted;
        return $this;
    }
    
    /**
     * Set city
     *
     * @param City $city
     *
     * @return Event
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
     * Set country
     *
     * @param Country $country
     *
     * @return Event
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param Region $region
     *
     * @return Event
     */
    public function setRegion(Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set usr
     *
     * @param User $usr
     *
     * @return Event
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
     * @return ArrayCollection $ideas
     */
    public function getIdeas()
    {
        return $this->ideas;
    }

    public function getIdeasCount()
    {
        return count($this->ideas);
    }
    public function getValidatedIdeasCount()
    {
        $count = 0;
        foreach ($this->ideas as $idea) {
            if ($idea->getIdeaValidated()) 
                $count++;
        }
        return $count;
    }
    /**
     * @return Chat $chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @param \DGIModule\Entity\Chat $chat
     * @return Event
     */
    public function setChat($chat)
    {
        $this->chat = $chat;
        return $this;
    }
    /**
     * @param string $eventUUID
     * @return Event
     */
    public function setEventUUID($eventUUID)
    {
        $this->eventUUID = $eventUUID;
        return $this;
    }


    
}
