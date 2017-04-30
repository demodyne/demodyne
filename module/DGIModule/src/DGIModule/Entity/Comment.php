<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use DGIModule\Entity\Program;
use DGIModule\Entity\Proposal;
use DGIModule\Entity\User;
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
     * @ORM\Column(name="com_text", type="text", length=65535, nullable=false)
     */
    private $comText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="com_created_date", type="utcdatetime", nullable=false)
     */
    private $comCreatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="com_uuid", type="uuid", length=36, nullable=true)
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
     * @var Proposal
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Proposal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop = null;

    /**
     * @var Program
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Program")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prog_id", referencedColumnName="prog_id")
     * })
     */
    private $prog = null;

    /**
     * @var \DGIModule\Entity\Article
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article_id", referencedColumnName="article_id")
     * })
     */
    private $article = null;

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return Comment
     */
    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @var User
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
     * @param \DateTime $comCreatedDate
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
     * @param \DGIModule\Entity\Comment $com
     * @return Comment
     */
    public function setCom(Comment $com = null)
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
     * @param Proposal $prop
     * @return Comment
     */
    public function setProp(Proposal $prop = null)
    {
        $this->prop = $prop;

        return $this;
    }

    /**
     * Get comProp
     *
     * @return Proposal
     */
    public function getProp()
    {
        return $this->prop;
    }

    /**
     * Set comScn
     *
     * @param Program $prog
     * @return Comment
     */
    public function setProgram(Program $prog = null)
    {
        $this->prog = $prog;

        return $this;
    }

    /**
     * Get comScn
     *
     * @return Program
     */
    public function getProgram()
    {
        return $this->prog;
    }

    /**
     * Set comUsr
     *
     * @param User $usr
     * @return Comment
     */
    public function setUsr(User $usr = null)
    {
        $this->usr = $usr;

        return $this;
    }

    /**
     * Get comUsr
     *
     * @return User
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
    /**
     * @param string $comUUID
     * @return Comment
     */
    public function setComUUID($comUUID)
    {
        $this->comUUID = $comUUID;
        return $this;
    }

    
}
