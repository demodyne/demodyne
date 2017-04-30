<?php

namespace DGIModule\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * DGIModule\Entity\Ideas
 *
 * @ORM\Table(name="dgi_ideas", indexes={@ORM\Index(name="fk_dgi_ideas_1_idx", columns={"usr_id"}), 
 *                                       @ORM\Index(name="fk_dgi_ideas_2_idx", columns={"cat_id"}), 
 *                                       @ORM\Index(name="fk_dgi_ideas_3_idx", columns={"event_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\IdeaRepository")
 */
class Idea
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idea_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ideaId;

    /**
     * @var string
     *
     * @ORM\Column(name="idea_name", type="string", length=100, nullable=true)
     */
    private $ideaName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="idea_description", type="text", length=65535, nullable=true)
     */
    private $ideaDescription= '';

    /**
     * @var string
     *
     * @ORM\Column(name="idea_uuid", type="uuid", length=36, nullable=true)
     */
    private $ideaUUID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="idea_created_date", type="utcdatetime", nullable=false)
     */
    private $ideaCreatedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="idea_status", type="integer", nullable=true)
     */
    private $ideaStatus = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="idea_position", type="integer", nullable=true)
     */
    private $ideaPosition = 0;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="idea_validated", type="boolean", nullable=true)
     */
    private $ideaValidated = 0;
    
    

    /**
     * @var \DGIModule\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;

    /**
     * @var \DGIModule\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     * })
     */
    private $cat;

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
     * @var \DGIModule\Entity\Proposal
     *
     * @ORM\OneToOne(targetEntity="DGIModule\Entity\Proposal", cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop;
    
    /**
     * @var \Doctrine\Common\Collections\Collection|Proposal[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Proposal", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_idea_proposals",
     *  joinColumns={
     *      @ORM\JoinColumn(name="idea_id", referencedColumnName="idea_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     *  }
     * )
     */
    private $proposals;
    
    public function __construct()
    {
        $this->proposals = new ArrayCollection();
    }
    
    public function getProposals()
    {
        $proposals = [];
        foreach ($this->proposals as $proposal) {
            if (!$proposal->getPropDeletedDate()) {
                $proposals[] = $proposal;
            }
        }
        return $proposals;
    }
    
    /**
     * @param Proposal $proposal
     */
    public function addProposal(Proposal $proposal)
    {
        if ($this->proposals->contains($proposal)) {
            return;
        }
        $this->proposals->add($proposal);
    }
    
    /**
     * @param Proposal $proposal
     */
    public function removeProposal(Proposal $proposal)
    {
        if (!$this->proposals->contains($proposal)) {
            return;
        }
    
        $this->proposals->removeElement($proposal);
    
    }
    
    public function clearProposals() {
        $this->proposals->clear();
    }
    
    /**
     * @return string $ideaName
     */
    public function getIdeaName()
    {
        return $this->ideaName;
    }

    /**
     * @param string $ideaName
     * @return $this
     */
    public function setIdeaName($ideaName)
    {
        $this->ideaName = $ideaName;
        return $this;
    }

    /**
     * @return string $ideaDescription
     */
    public function getIdeaDescription()
    {
        return $this->ideaDescription;
    }

    /**
     * @param string $ideaDescription
     * @return $this
     */
    public function setIdeaDescription($ideaDescription)
    {
        $this->ideaDescription = $ideaDescription;
        return $this;
    }

    /**
     * @return \DateTime $ideaCreatedDate
     */
    public function getIdeaCreatedDate()
    {
        return $this->ideaCreatedDate;
    }

    /**
     * @param \DateTime $ideaCreatedDate
     * @return $this
     */
    public function setIdeaCreatedDate($ideaCreatedDate)
    {
        $this->ideaCreatedDate = $ideaCreatedDate;
        return $this;
    }

    /**
     * @return int $ideaStatus
     */
    public function getIdeaStatus()
    {
        return $this->ideaStatus;
    }

    /**
     * @param number $ideaStatus
     * @return $this
     */
    public function setIdeaStatus($ideaStatus)
    {
        $this->ideaStatus = $ideaStatus;
        return $this;
    }

    /**
     * @return int $ideaPosition
     */
    public function getIdeaPosition()
    {
        return $this->ideaPosition;
    }

    /**
     * @param number $ideaPosition
     * @return $this
     */
    public function setIdeaPosition($ideaPosition)
    {
        $this->ideaPosition = $ideaPosition;
        return $this;
    }

    /**
     * @return User $usr
     */
    public function getUsr()
    {
        return $this->usr;
    }

    /**
     * @param User $usr
     * @return $this
     */
    public function setUsr($usr)
    {
        $this->usr = $usr;
        return $this;
    }

    /**
     * @return Category $cat
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * @param Category $cat
     * @return $this
     */
    public function setCat($cat)
    {
        $this->cat = $cat;
        return $this;
    }

    /**
     * @return Event $event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param \DGIModule\Entity\Event $event
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return int $ideaId
     */
    public function getIdeaId()
    {
        return $this->ideaId;
    }

    /**
     * @return string $ideaUUID
     */
    public function getIdeaUUID()
    {
        return $this->ideaUUID;
    }
    /**
     * @return boolean $ideaValidated
     */
    public function getIdeaValidated()
    {
        return $this->ideaValidated;
    }

    /**
     * @param boolean $ideaValidated
     * @return $this
     */
    public function setIdeaValidated($ideaValidated)
    {
        $this->ideaValidated = $ideaValidated;
        return $this;
    }

    /**
     * @param string $ideaUUID
     * @return $this
     */
    public function setIdeaUUID($ideaUUID)
    {
        $this->ideaUUID = $ideaUUID;
        return $this;
    }
    /**
     * @return Proposal $prop
     */
    public function getProp()
    {
        return $this->prop;
    }

    /**
     * @param Proposal $prop
     * @return $this
     */
    public function setProp($prop)
    {
        $this->prop = $prop;
        return $this;
    }

}

