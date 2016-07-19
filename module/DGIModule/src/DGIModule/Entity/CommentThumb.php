<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

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
     * @var \DGIModule\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;

    /**
     * @var \DGIModule\Entity\Comment
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
     * @param \DGIModule\Entity\User $usr
     *
     * @return CommentThumb
     */
    public function setUsr(\DGIModule\Entity\User $usr = null)
    {
        $this->usr = $usr;

        return $this;
    }

    /**
     * Get usr
     *
     * @return \DGIModule\Entity\User
     */
    public function getUsr()
    {
        return $this->usr;
    }

    /**
     * Set com
     *
     * @param \DGIModule\Entity\Comment $com
     *
     * @return CommentThumb
     */
    public function setCom(\DGIModule\Entity\Comment $com = null)
    {
        $this->com = $com;

        return $this;
    }

    /**
     * Get com
     *
     * @return \DGIModule\Entity\Comment
     */
    public function getCom()
    {
        return $this->com;
    }
}
