<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */
 
namespace DGIModule\Entity;

use DGIModule\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="dgi_report", indexes={@ORM\Index(name="rep_usr_fk_idx", columns={"usr_id"})})
 * @ORM\Entity
 */
class Report
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rep_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $repId;

    /**
     * @var string
     *
     * @ORM\Column(name="rep_type", type="string", length=45, nullable=false)
     */
    private $repType;

    /**
     * @var string
     *
     * @ORM\Column(name="rep_uuid", type="string", length=36, nullable=false)
     */
    private $repUUID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rep_created_date", type="utcdatetime", nullable=true)
     */
    private $repCreatedDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rep_reason", type="string", length=300, nullable=true)
     */
    private $repReason = '';
    
    /**
     * @var string
     *
     * @ORM\Column(name="rep_description", type="string", length=5000, nullable=true)
     */
    private $repDescription = '';
    
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
     * Get repId
     *
     * @return integer 
     */
    public function getRepId()
    {
        return $this->repId;
    }

    /**
     * Set repType
     *
     * @param string $repType
     * @return Report
     */
    public function setRepType($repType)
    {
        $this->repType = $repType;

        return $this;
    }

    /**
     * Get repType
     *
     * @return string 
     */
    public function getRepType()
    {
        return $this->repType;
    }

    /**
     * Set repUuid
     *
     * @param string $repUUID
     *
     * @return Report
     */
    public function setRepUUID($repUUID)
    {
        $this->repUUID = $repUUID;
    
        return $this;
    }

    /**
     * Get repUuid
     *
     * @return string 
     */
    public function getRepUUID()
    {
        return $this->repUUID;
    }

    /**
     * Set repCreatedDate
     *
     * @param \DateTime $repCreatedDate
     * @return Report
     */
    public function setRepCreatedDate($repCreatedDate)
    {
        $this->repCreatedDate = $repCreatedDate;

        return $this;
    }

    /**
     * Get repCreatedDate
     *
     * @return \DateTime 
     */
    public function getRepCreatedDate()
    {
        return $this->repCreatedDate;
    }
    
    /**
     * Set repReason
     *
     * @param string $repReason
     *
     * @return Report
     */
    public function setRepReason($repReason)
    {
        $this->repReason = $repReason;
    
        return $this;
    }
    
    /**
     * Get repReason
     *
     * @return string
     */
    public function getRepReason()
    {
        return $this->repReason;
    }
    
    /**
     * Set repDescription
     *
     * @param string $repDescription
     *
     * @return Report
     */
    public function setRepDescription($repDescription)
    {
        $this->repDescription = $repDescription;
    
        return $this;
    }
    
    /**
     * Get repDescription
     *
     * @return string
     */
    public function getRepDescription()
    {
        return $this->repDescription;
    }
    
    /**
     * Set usr
     *
     * @param User $usr
     *
     * @return Report
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
}
