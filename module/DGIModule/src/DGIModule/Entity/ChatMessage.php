<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use DGIModule\Entity\Chat;
use DGIModule\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * DGIModule/Entity/DgiMessages
 *
 * @ORM\Table(name="dgi_chat_messages", indexes={@ORM\Index(name="fk_dgi_chat_messages_1_idx", columns={"chat_id"}), @ORM\Index(name="fk_dgi_chat_messages_2_idx", columns={"usr_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\ChatMessageRepository")
 */
class ChatMessage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="msg_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $msgId;

    /**
     * @var string
     *
     * @ORM\Column(name="msg_text", type="text", length=65535, nullable=true)
     */
    private $msgText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="msg_date_time", type="utcdatetime", nullable=true)
     */
    private $msgDateTime;
    
    /**
     * @var string
     *
     * @ORM\Column(name="msg_entity_uuid", type="string", length=36, nullable=true)
     */
    private $msgEntityUUID;
    
    /**
     * @var string
     *
     * @ORM\Column(name="msg_entity_name", type="string", length=100, nullable=true)
     */
    private $msgEntityName = '';
    
    /**
     * @var Chat
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Chat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chat_id", referencedColumnName="chat_id")
     * })
     */
    private $chat;
    
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blocked_usr_id", referencedColumnName="usr_id")
     * })
     */
    private $blockedUsr;
    
    
    /**
     * @return User $blockedUsr
     */
    public function getBlockedUsr()
    {
        return $this->blockedUsr;
    }

    /**
     * @param User $blockedUsr
     * @return ChatMessage
     */
    public function setBlockedUsr($blockedUsr)
    {
        $this->blockedUsr = $blockedUsr;
        return $this;
    }

    /**
     * @return Chat $chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     * @return ChatMessage
     */
    public function setChat(Chat $chat)
    {
        $this->chat = $chat;
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
     * @return ChatMessage
     */
    public function setUsr(User $usr)
    {
        $this->usr = $usr;
        return $this;
    }

    /**
     * @return int $msgId
     */
    public function getMsgId()
    {
        return $this->msgId;
    }

    
    /**
     * @return string $msgText
     */
    public function getMsgText()
    {
        return $this->msgText;
    }

    /**
     * @param string $msgText
     * @return ChatMessage
     */
    public function setMsgText($msgText)
    {
        $this->msgText = $msgText;
        return $this;
    }

    /**
     * @return \DateTime $msgDateTime
     */
    public function getMsgDateTime()
    {
        return $this->msgDateTime;
    }

    /**
     * @param \DateTime $msgDateTime
     * @return ChatMessage
     */
    public function setMsgDateTime($msgDateTime)
    {
        $this->msgDateTime = $msgDateTime;
        return $this;
    }
    /**
     * @return string $msgEntityUUID
     */
    public function getMsgEntityUUID()
    {
        return $this->msgEntityUUID;
    }

    /**
     * @param string $msgEntityUUID
     * @return ChatMessage
     */
    public function setMsgEntityUUID($msgEntityUUID)
    {
        $this->msgEntityUUID = $msgEntityUUID;
        return $this;
    }
    /**
     * @return string $msgEntityName
     */
    public function getMsgEntityName()
    {
        return $this->msgEntityName;
    }

    /**
     * @param string $msgEntityName
     * @return ChatMessage
     */
    public function setMsgEntityName($msgEntityName)
    {
        $this->msgEntityName = $msgEntityName;
        return $this;
    }





}

