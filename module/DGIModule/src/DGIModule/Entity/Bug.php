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
 * Bug
 *
 * @ORM\Table(name="dgi_bugs", indexes={@ORM\Index(name="bug_usr_fk_idx", columns={"usr_id"})})
 * @ORM\Entity
 */
class Bug
{
    /**
     * @var integer
     *
     * @ORM\Column(name="bug_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bugId;

    /**
     * @var string
     *
     * @ORM\Column(name="bug_title", type="string", length=100, nullable=false)
     */
    private $bugTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="bug_description", type="string", length=5000, nullable=false)
     */
    private $bugDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="bug_image", type="string", length=200, nullable=true)
     */
    private $bugImage;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bug_created_date", type="utcdatetime", nullable=true)
     */
    private $bugCreatedDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User", cascade={"persist", "merge"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;



    /**
     * Get bugId
     *
     * @return integer
     */
    public function getBugId()
    {
        return $this->bugId;
    }

    /**
     * Set bugTitle
     *
     * @param string $bugTitle
     *
     * @return Bug
     */
    public function setBugTitle($bugTitle)
    {
        $this->bugTitle = $bugTitle;

        return $this;
    }

    /**
     * Get bugTitle
     *
     * @return string
     */
    public function getBugTitle()
    {
        return $this->bugTitle;
    }

    /**
     * Set bugDescription
     *
     * @param string $bugDescription
     *
     * @return Bug
     */
    public function setBugDescription($bugDescription)
    {
        $this->bugDescription = $bugDescription;

        return $this;
    }

    /**
     * Get bugDescription
     *
     * @return string
     */
    public function getBugDescription()
    {
        return $this->bugDescription;
    }

    /**
     * Set bugImage
     *
     * @param string $bugImage
     *
     * @return Bug
     */
    public function setBugImage($bugImage)
    {
        $this->bugImage = $bugImage;

        return $this;
    }

    /**
     * Get bugImage
     *
     * @return string
     */
    public function getBugImage()
    {
        return $this->bugImage;
    }
    
    /**
     * Set bugCreatedDate - created automatically at INSERT
     *
     * @param \DateTime $bugCreatedDate
     *
     * @return Bug
     */
//     public function setBugCreatedDate($bugCreatedDate)
//     {
//         $this->bugCreatedDate = $bugCreatedDate;
    
//         return $this;
//     }
    
    /**
     * Get bugCreatedDate
     *
     * @return \DateTime
     */
    public function getBugCreatedDate()
    {
        return $this->bugCreatedDate;
    }

    /**
     * Set usr
     *
     * @param User $usr
     *
     * @return Bug
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
     * @param \DateTime $bugCreatedDate
     */
    public function setBugCreatedDate($bugCreatedDate)
    {
        $this->bugCreatedDate = $bugCreatedDate;
    }

}
