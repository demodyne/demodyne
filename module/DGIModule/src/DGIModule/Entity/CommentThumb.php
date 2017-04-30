<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use DGIModule\Entity\Comment;
use DGIModule\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommentThumb
 *
 * @ORM\Table(name="dgi_comment_thumb", indexes={@ORM\Index(name="thumb_usr_id_idx", columns={"usr_id"}), @ORM\Index(name="thumb_com_id_idx", columns={"com_id"})})
 * @ORM\Entity
 */
class CommentThumb
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="up", type="integer", nullable=true)
     */
    private $up = '0';

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
     * @var Comment
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Comment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="com_id", referencedColumnName="com_id")
     * })
     */
    private $com;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set up
     *
     * @param integer $up
     *
     * @return CommentThumb
     */
    public function setUp($up)
    {
        $this->up = $up;

        return $this;
    }

    /**
     * Get up
     *
     * @return integer
     */
    public function getUp()
    {
        return $this->up;
    }

    /**
     * Set usr
     *
     * @param User $usr
     *
     * @return CommentThumb
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
     * Set com
     *
     * @param Comment $com
     *
     * @return CommentThumb
     */
    public function setCom(Comment $com = null)
    {
        $this->com = $com;

        return $this;
    }

    /**
     * Get com
     *
     * @return Comment
     */
    public function getCom()
    {
        return $this->com;
    }
}
