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
 * Import/Entity/DgiChats
 *
 * @ORM\Table(name="dgi_chats")
 * @ORM\Entity
 */
class Chat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="chat_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $chatId;

    /**
     * @var string
     *
     * @ORM\Column(name="chat_entity_type", type="string", length=45, nullable=true)
     */
    private $chatEntityType;

    /**
     * @var string
     *
     * @ORM\Column(name="chat_entity_uuid", type="string", length=36, nullable=true)
     */
    private $chatEntityUUID;
    
    /**
     * @var string
     *
     * @ORM\Column(name="chat_uuid", type="uuid", length=36, nullable=true)
     */
    private $chatUUID;
    
    /**
     * @var string
     *
     * @ORM\Column(name="chat_title", type="string", length=500, nullable=true)
     */
    private $chatTitle = '';
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="chat_start_date", type="utcdatetime", nullable=true)
     */
    private $chatStartDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="chat_end_date", type="utcdatetime", nullable=true)
     */
    private $chatEndDate;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="chat_opened", type="boolean", nullable=true)
     */
    private $chatOpened = 1;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;
    
    function __construct($entityType = null, $entityUUID = null, User $user = null, $title="") {
        $this->chatEntityType=$entityType;
        $this->chatEntityUUID =$entityUUID;
        $this->usr = $user;
        $this->chatTitle = $title;
    }
    
    
    /**
     * @return int $chatId
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @return string $chatEntityType
     */
    public function getChatEntityType()
    {
        return $this->chatEntityType;
    }

    /**
     * @param string $chatEntityType
     * @return Chat
     */
    public function setChatEntityType($chatEntityType)
    {
        $this->chatEntityType = $chatEntityType;
        return $this;
    }

    /**
     * @return string $chatEntityUUID
     */
    public function getChatEntityUUID()
    {
        return $this->chatEntityUUID;
    }

    /**
     * @param string $chatEntityUUID
     * @return Chat
     */
    public function setChatEntityUUID($chatEntityUUID)
    {
        $this->chatEntityUUID = $chatEntityUUID;
        return $this;
    }
    /**
     * @return string $chatUUID
     */
    public function getChatUUID()
    {
        return $this->chatUUID;
    }
    /**
     * @return string $chatTitle
     */
    public function getChatTitle()
    {
        return $this->chatTitle;
    }

    /**
     * @param string $chatTitle
     * @return Chat
     */
    public function setChatTitle($chatTitle)
    {
        $this->chatTitle = $chatTitle;
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
     * @return Chat
     */
    public function setUsr($usr)
    {
        $this->usr = $usr;
        return $this;
    }
    /**
     * @return \DateTime $chatStartDate
     */
    public function getChatStartDate()
    {
        return $this->chatStartDate;
    }

    /**
     * @param \DateTime $chatStartDate
     * @return Chat
     */
    public function setChatStartDate($chatStartDate)
    {
        $this->chatStartDate = $chatStartDate;
        return $this;
    }

    /**
     * @return \DateTime $chatEndDate
     */
    public function getChatEndDate()
    {
        return $this->chatEndDate;
    }

    /**
     * @param \DateTime $chatEndDate
     * @return Chat
     */
    public function setChatEndDate($chatEndDate)
    {
        $this->chatEndDate = $chatEndDate;
        return $this;
    }

    /**
     * @return boolean $chatOpened
     */
    public function getChatOpened()
    {
        return $this->chatOpened;
    }

    /**
     * @param boolean $chatOpened
     * @return Chat
     */
    public function setChatOpened($chatOpened)
    {
        $this->chatOpened = $chatOpened;
        return $this;
    }
    /**
     * @param string $chatUUID
     * @return Chat
     */
    public function setChatUUID($chatUUID)
    {
        $this->chatUUID = $chatUUID;
        return $this;
    }


}

