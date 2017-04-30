<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */
 
namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlockedUser
 *
 * @ORM\Table(name="dgi_blocked_users", indexes={@ORM\Index(name="fk_blocked_users_1_idx", columns={"usr_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\BlockedUserRepository")
 */
class BlockedUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="block_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $blockId;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_uuid", type="string", length=36, nullable=false)
     */
    private $entityUUID;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_type", type="string", length=45, nullable=true)
     */
    private $entityType;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="block_created_date", type="utcdatetime", nullable=true)
     */
    private $blockCreatedDate;

    /**
     * @var \DGIModule\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;
    
    function __construct() {
        $this->blockCreatedDate = new \DateTime();
    }
    /**
     * @return \DateTime $blockCreatedDate
     */
    public function getBlockCreatedDate()
    {
        return $this->blockCreatedDate;
    }

    /**
     * @param \DateTime $blockCreatedDate
     * @return BlockedUser
     */
    public function setBlockCreatedDate($blockCreatedDate)
    {
        $this->blockCreatedDate = $blockCreatedDate;
        return $this;
    }

    /**
     * @return string $entityUuid
     */
    public function getEntityUUID()
    {
        return $this->entityUUID;
    }

    /**
     * @param string $entityUuid
     * @return BlockedUser
     */
    public function setEntityUUID($entityUUID)
    {
        $this->entityUUID = $entityUUID;
        return $this;
    }

    /**
     * @return string $entityType
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @param string $entityType
     * @return BlockedUser
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
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
     * @return BlockedUser
     */
    public function setUsr($usr)
    {
        $this->usr = $usr;
        return $this;
    }

    /**
     * @return int $blockId
     */
    public function getBlockId()
    {
        return $this->blockId;
    }


}
