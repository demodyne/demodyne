<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="dgi_news", indexes={@ORM\Index(name="city_id_idx", columns={"city_id"}), @ORM\Index(name="scn_id_idx", columns={"scn_id"}), @ORM\Index(name="prop_id_idx", columns={"prop_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\NewsRepository")
 */
class News
{
    /**
     * @var integer
     *
     * @ORM\Column(name="news_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $newsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="news_type", type="integer", nullable=false)
     */
    private $newsType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_created_date", type="datetime", nullable=true)
     */
    private $newsCreatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="news_uuid", type="string", length=36, nullable=true)
     */
    private $newsUuid;

    /**
     * @var \DGIModule\Entity\Proposal
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Proposal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop;

    /**
     * @var \DGIModule\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="city_id")
     * })
     */
    private $city;

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
     * Get newsId
     *
     * @return integer 
     */
    public function getNewsId()
    {
        return $this->newsId;
    }

    /**
     * Set newsType
     *
     * @param integer $newsType
     * @return News
     */
    public function setNewsType($newsType)
    {
        $this->newsType = $newsType;

        return $this;
    }

    /**
     * Get newsType
     *
     * @return integer 
     */
    public function getNewsType()
    {
        return $this->newsType;
    }

    /**
     * Set newsCreatedDate
     *
     * @param \DateTime $newsCreatedDate
     * @return News
     */
    public function setNewsCreatedDate($newsCreatedDate)
    {
        $this->newsCreatedDate = $newsCreatedDate;

        return $this;
    }

    /**
     * Get newsCreatedDate
     *
     * @return \DateTime 
     */
    public function getNewsCreatedDate()
    {
        return $this->newsCreatedDate;
    }

    /**
     * Get newsUuid
     *
     * @return string 
     */
    public function getNewsUuid()
    {
        return $this->newsUuid;
    }

    /**
     * Set prop
     *
     * @param \DGIModule\Entity\Proposal $prop
     * @return News
     */
    public function setProp(\DGIModule\Entity\Proposal $prop = null)
    {
        $this->prop = $prop;

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
     * Set city
     *
     * @param \DGIModule\Entity\City $city
     * @return News
     */
    public function setCity(\DGIModule\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \DGIModule\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    
    /**
     * Set usr
     *
     * @param \DGIModule\Entity\User $usr
     *
     * @return News
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
}
