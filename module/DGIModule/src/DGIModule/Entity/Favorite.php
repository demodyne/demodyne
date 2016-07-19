<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorite
 *
 * @ORM\Table(name="dgi_favorites", indexes={@ORM\Index(name="prop_id_idx", columns={"prop_id"}), @ORM\Index(name="usr_id_idx", columns={"usr_id"})})
 * @ORM\Entity
 */
class Favorite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="fav_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $favId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_check_date", type="datetime", nullable=false)
     */
    private $favLastCheckDate;

    /**
     * @var \Import\Entity\Proposal
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Proposal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop;

    /**
     * @var \Import\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;



    /**
     * Get favId
     *
     * @return integer 
     */
    public function getFavId()
    {
        return $this->favId;
    }

    /**
     * Set lastCheckDate
     *
     * @param \DateTime $lastCheckDate
     * @return Favorite
     */
    public function setFavLastCheckDate($lastCheckDate)
    {
        $this->favLastCheckDate = $lastCheckDate;

        return $this;
    }

    /**
     * Get lastCheckDate
     *
     * @return \DateTime 
     */
    public function getFavLastCheckDate()
    {
        return $this->favLastCheckDate;
    }

    /**
     * Set prop
     *
     * @param \Import\Entity\Proposal $prop
     * @return Favorite
     */
    public function setProp(\DGIModule\Entity\Proposal $prop = null)
    {
        $this->prop = $prop;
        $prop->addFavorite($this);

        return $this;
    }

    /**
     * Get prop
     *
     * @return \DGIModule\Entity\Proposal 
     */
    public function getProp()
    {
        return $this->prop;
    }

    /**
     * Set usr
     *
     * @param \DGIModule\Entity\User $usr
     * @return Favorite
     */
    public function setUsr(\DGIModule\Entity\User $usr = null)
    {
        $this->usr = $usr;
        $usr->addFavorite($this);

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
}
