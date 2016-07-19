<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * Comment
 *
 * @ORM\Table(name="dgi_comments", 
 *            indexes={@ORM\Index(name="com_com_fk_idx", columns={"com_com"}), 
 *                     @ORM\Index(name="com_usr_fk_idx", columns={"com_usr"}), 
 *                     @ORM\Index(name="com_prop_fk_idx", columns={"com_prop"}), 
 *                     @ORM\Index(name="com_scn_fk_idx", columns={"com_scn"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="com_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $comId;

    /**
     * @var string
     *
     * @ORM\Column(name="com_text", type="string", length=1000, nullable=false)
     */
    private $comText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="com_created_date", type="datetime", nullable=false)
     */
    private $comCreatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="com_uuid", type="string", length=36, nullable=true)
     */
    private $comUUID;

    /**
     * @var \DGIModule\Entity\Comment
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Comment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="com_com", referencedColumnName="com_id")
     * })
     */
    private $com = null;

    /**
     * @var \DGIModule\Entity\Proposal
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Proposal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop = null;

    /**
     * @var \DGIModule\Entity\Program
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Program")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prog_id", referencedColumnName="prog_id")
     * })
     */
    private $prog = null;

    /**
     * @var \DGIModule\Entity\DgiUsers
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="com_usr", referencedColumnName="usr_id")
     * })
     */
    private $usr;
    
    /**
     * @ORM\OneToMany(targetEntity="CommentThumb", mappedBy="com")
     * 
     */
    protected $thumbs;
    
    /**
     * Get comId
     *
     * @return integer 
     */
    public function getComId()
    {
        return $this->comId;
    }

    /**
     * Set comText
     *
     * @param string $comText
     * @return Comment
     */
    public function setComText($comText)
    {
        $this->comText = $comText;

        return $this;
    }

    /**
     * Get comText
     *
     * @return string 
     */
    public function getComText()
    {
        return $this->comText;
    }

    /**
     * Set comCreated
     *
     * @param \DateTime $comCreated
     * @return Comment
     */
    public function setComCreatedDate($comCreatedDate)
    {
        $this->comCreatedDate = $comCreatedDate;

        return $this;
    }

    /**
     * Get comCreated
     *
     * @return \DateTime 
     */
    public function getComCreatedDate()
    {
        return $this->comCreatedDate;
    }

        /**
     * Get comUuid
     *
     * @return string 
     */
    public function getComUUID()
    {
        return $this->comUUID;
    }

    /**
     * Set comCom
     *
     * @param \DGIModule\Entity\Comment $comCom
     * @return Comment
     */
    public function setCom(\DGIModule\Entity\Comment $com = null)
    {
        $this->com = $com;

        return $this;
    }

    /**
     * Get comCom
     *
     * @return \DGIModule\Entity\Comment 
     */
    public function getCom()
    {
        return $this->com;
    }

    /**
     * Set comProp
     *
     * @param \DGIModule\Entity\Proposal $comProp
     * @return Comment
     */
    public function setProp(\DGIModule\Entity\Proposal $prop = null)
    {
        $this->prop = $prop;

        return $this;
    }

    /**
     * Get comProp
     *
     * @return \DGIModule\Entity\Proposal 
     */
    public function getProp()
    {
        return $this->prop;
    }

    /**
     * Set comScn
     *
     * @param \DGIModule\Entity\Program $comScn
     * @return Comment
     */
    public function setProgram(\DGIModule\Entity\Program $prog = null)
    {
        $this->prog = $prog;

        return $this;
    }

    /**
     * Get comScn
     *
     * @return \DGIModule\Entity\Program 
     */
    public function getProgram()
    {
        return $this->prog;
    }

    /**
     * Set comUsr
     *
     * @param \DGIModule\Entity\User $comUsr
     * @return Comment
     */
    public function setUsr(\DGIModule\Entity\User $usr = null)
    {
        $this->usr = $usr;

        return $this;
    }

    /**
     * Get comUsr
     *
     * @return \DGIModule\Entity\User 
     */
    public function getUsr()
    {
        return $this->usr;
    }
    
    public function getThumbsUp()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('up', 1));
        return $this->thumbs->matching($criteria);
    }
    
    public function getThumbsDown()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('up', 0));
        return $this->thumbs->matching($criteria);
    }
    
    public function hasThumbFromUser($user)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('usr', $user));
        return count($this->thumbs->matching($criteria))>0;
    }
    
}
