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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Program
 *
 * @ORM\Table(name="dgi_programs", indexes={@ORM\Index(name="id_user_idx1", columns={"usr_id"}), @ORM\Index(name="id_city_idx1", columns={"city_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\ProgramRepository")
 */
class Program
{
    /**
     * @var integer
     *
     * @ORM\Column(name="prog_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $progId;

    /**
     * @var integer
     *
     * @ORM\Column(name="prog_level", type="integer", nullable=false)
     */
    private $progLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="prog_name", type="string", length=50, nullable=true)
     */
    private $progName;

    /**
     * @var string
     *
     * @ORM\Column(name="prog_description", type="text", length=65535, nullable=true)
     */
    private $progDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prog_created_date", type="utcdatetime", nullable=false)
     */
    private $progCreatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="prog_uuid", type="uuid", length=36, nullable=true)
     */
    private $progUUID;

    /**
     * @var integer
     *
     * @ORM\Column(name="prog_saved", type="integer", nullable=true)
     */
    private $progSaved = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="prog_saved_name", type="string", length=50, nullable=true)
     */
    private $progSavedName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prog_saved_date", type="utcdatetime", nullable=true)
     */
    private $progSavedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prog_deleted_date", type="utcdatetime", nullable=true)
     */
    private $progDeletedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="prog_deleted_usr", type="integer", nullable=true)
     */
    private $progDeletedUsr;

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
     * @var \Doctrine\Common\Collections\Collection|ProposalProgram[]
     *
     * @ORM\OneToMany(targetEntity="DGIModule\Entity\ProposalProgram", mappedBy="prog")
     */
    private $programProposals;
    
    /**
     * @var \Doctrine\Common\Collections\Collection|Comment[]
     *
     * @ORM\OneToMany(targetEntity="DGIModule\Entity\Comment", mappedBy="prog")
     */
    private $comments;
    
    public function __construct()
    {
        $this->programProposals = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Get progId
     *
     * @return integer
     */
    public function getProgId()
    {
        return $this->progId;
    }

    /**
     * Set progLevel
     *
     * @param integer $progLevel
     *
     * @return Program
     */
    public function setProgLevel($progLevel)
    {
        $this->progLevel = $progLevel;

        return $this;
    }

    /**
     * Get progLevel
     *
     * @return integer
     */
    public function getProgLevel()
    {
        return $this->progLevel;
    }

    /**
     * Set progName
     *
     * @param string $progName
     *
     * @return Program
     */
    public function setProgName($progName)
    {
        $this->progName = $progName;

        return $this;
    }

    /**
     * Get progName
     *
     * @return string
     */
    public function getProgName()
    {
        return $this->progName;
    }

    /**
     * Set progDescription
     *
     * @param string $progDescription
     *
     * @return Program
     */
    public function setProgDescription($progDescription)
    {
        $this->progDescription = $progDescription;

        return $this;
    }

    /**
     * Get progDescription
     *
     * @return string
     */
    public function getProgDescription()
    {
        return $this->progDescription;
    }

    /**
     * Set progCreatedDate
     *
     * @param \DateTime $progCreatedDate
     *
     * @return Program
     */
    public function setProgCreatedDate($progCreatedDate)
    {
        $this->progCreatedDate = $progCreatedDate;

        return $this;
    }

    /**
     * Get progCreatedDate
     *
     * @return \DateTime
     */
    public function getProgCreatedDate()
    {
        return $this->progCreatedDate;
    }

    /**
     * Get progUuid
     *
     * @return string
     */
    public function getProgUUID()
    {
        return $this->progUUID;
    }

    /**
     * Set progSaved
     *
     * @param integer $progSaved
     *
     * @return Program
     */
    public function setProgSaved($progSaved)
    {
        $this->progSaved = $progSaved;

        return $this;
    }

    /**
     * Get progSaved
     *
     * @return integer
     */
    public function getProgSaved()
    {
        return $this->progSaved;
    }

    /**
     * Set progSavedName
     *
     * @param string $progSavedName
     *
     * @return Program
     */
    public function setProgSavedName($progSavedName)
    {
        $this->progSavedName = $progSavedName;

        return $this;
    }

    /**
     * Get progSavedName
     *
     * @return string
     */
    public function getProgSavedName()
    {
        return $this->progSavedName;
    }

    /**
     * Set progSavedDate
     *
     * @param \DateTime $progSavedDate
     *
     * @return Program
     */
    public function setProgSavedDate($progSavedDate)
    {
        $this->progSavedDate = $progSavedDate;

        return $this;
    }

    /**
     * Get progSavedDate
     *
     * @return \DateTime
     */
    public function getProgSavedDate()
    {
        return $this->progSavedDate;
    }

    /**
     * Set progDeletedDate
     *
     * @param \DateTime $progDeletedDate
     *
     * @return Program
     */
    public function setProgDeletedDate($progDeletedDate)
    {
        $this->progDeletedDate = $progDeletedDate;

        return $this;
    }

    /**
     * Get progDeletedDate
     *
     * @return \DateTime
     */
    public function getProgDeletedDate()
    {
        return $this->progDeletedDate;
    }

    /**
     * Set progDeletedUsr
     *
     * @param integer $progDeletedUsr
     *
     * @return Program
     */
    public function setProgDeletedUsr($progDeletedUsr)
    {
        $this->progDeletedUsr = $progDeletedUsr;

        return $this;
    }

    /**
     * Get progDeletedUsr
     *
     * @return integer
     */
    public function getProgDeletedUsr()
    {
        return $this->progDeletedUsr;
    }

    /**
     * Set city
     *
     * @param City $city
     *
     * @return Program
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
     * @return Program
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
     * Get the proposals list
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProposals() {
    
        $proposals = new ArrayCollection();
    
        foreach ($this->programProposals as $proposalProgram) {
            if (!$proposalProgram->getProp()->getPropDeletedDate()) {
                $proposals->add($proposalProgram->getProp());
            }
        }
    
        return $proposals;
    }
    
    public function hasProposal(Proposal $proposal) {
        
        foreach ($this->programProposals as $proposalProgram) {
            $propItem = $proposalProgram->getProp();
            if ($propItem==$proposal && !$propItem->getPropDeletedDate()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get the comments list
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments() {
        return $this->comments;
    }
    
    /**
     * @param string $progUUID
     * @return Program
     */
    public function setProgUUID($progUUID)
    {
        $this->progUUID = $progUUID;
        return $this;
    }

}
