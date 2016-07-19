<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 *
 * @ORM\Table(name="dgi_banners", indexes={@ORM\Index(name="fk_admin_banner_idx", columns={"admin_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\BannerRepository")
 */
class Banner
{
    /**
     * @var integer
     *
     * @ORM\Column(name="banner_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bannerId;

    /**
     * @var string
     *
     * @ORM\Column(name="banner_name", type="string", length=100, nullable=false)
     */
    private $bannerName;

    /**
     * @var string
     *
     * @ORM\Column(name="banner_description", type="string", length=1000, nullable=true)
     */
    private $bannerDescription = '';

    /**
     * @var string
     *
     * @ORM\Column(name="banner_image", type="string", length=200, nullable=true)
     */
    private $bannerImage = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="banner_created_date", type="datetime", nullable=true)
     */
    private $bannerCreatedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="banner_published", type="integer", nullable=true)
     */
    private $bannerPublished = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="banner_url", type="string", length=2000, nullable=true)
     */
    private $bannerUrl = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="banner_position", type="integer", nullable=true)
     */
    private $bannerPosition = -1;

    /**
     * @var string
     *
     * @ORM\Column(name="banner_uuid", type="string", length=36, nullable=true)
     */
    private $bannerUUID;

    /**
     * @var \DGIModule\Entity\Administration
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Administration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_id", referencedColumnName="admin_id")
     * })
     */
    private $admin;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="banner_level", type="integer", nullable=true)
     */
    private $bannerLevel = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="banner_full_city", type="integer", nullable=true)
     */
    private $bannerFullCity = 0;
    
    

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
     * @var \DGIModule\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country = null;


    /**
     * Get bannerId
     *
     * @return integer
     */
    public function getBannerId()
    {
        return $this->bannerId;
    }

    /**
     * Set bannerName
     *
     * @param string $bannerName
     *
     * @return Banner
     */
    public function setBannerName($bannerName)
    {
        $this->bannerName = $bannerName;

        return $this;
    }

    /**
     * Get bannerName
     *
     * @return string
     */
    public function getBannerName()
    {
        return $this->bannerName;
    }

    /**
     * Set bannerDescription
     *
     * @param string $bannerDescription
     *
     * @return Banner
     */
    public function setBannerDescription($bannerDescription)
    {
        $this->bannerDescription = $bannerDescription;

        return $this;
    }

    /**
     * Get bannerDescription
     *
     * @return string
     */
    public function getBannerDescription()
    {
        return $this->bannerDescription;
    }

    /**
     * Set bannerImage
     *
     * @param string $bannerImage
     *
     * @return Banner
     */
    public function setBannerImage($bannerImage)
    {
        $this->bannerImage = $bannerImage;

        return $this;
    }

    /**
     * Get bannerImage
     *
     * @return string
     */
    public function getBannerImage()
    {
        return $this->bannerImage;
    }

    /**
     * Set bannerCreatedDate
     *
     * @param \DateTime $bannerCreatedDate
     *
     * @return Banner
     */
    public function setBannerCreatedDate($bannerCreatedDate)
    {
        $this->bannerCreatedDate = $bannerCreatedDate;

        return $this;
    }

    /**
     * Get bannerCreatedDate
     *
     * @return \DateTime
     */
    public function getBannerCreatedDate()
    {
        return $this->bannerCreatedDate;
    }

    /**
     * Set bannerPublished
     *
     * @param integer $bannerPublished
     *
     * @return Banner
     */
    public function setBannerPublished($bannerPublished)
    {
        $this->bannerPublished = $bannerPublished;

        return $this;
    }

    /**
     * Get bannerPublished
     *
     * @return integer
     */
    public function getBannerPublished()
    {
        return $this->bannerPublished;
    }

    /**
     * Set bannerUrl
     *
     * @param string $bannerUrl
     *
     * @return Banner
     */
    public function setBannerUrl($bannerUrl)
    {
        $this->bannerUrl = $bannerUrl;

        return $this;
    }

    /**
     * Get bannerUrl
     *
     * @return string
     */
    public function getBannerUrl()
    {
        return $this->bannerUrl;
    }

    /**
     * Set bannerPosition
     *
     * @param integer $bannerPosition
     *
     * @return Banner
     */
    public function setBannerPosition($bannerPosition)
    {
        $this->bannerPosition = $bannerPosition;

        return $this;
    }

    /**
     * Get bannerPosition
     *
     * @return integer
     */
    public function getBannerPosition()
    {
        return $this->bannerPosition;
    }

    /**
     * Get bannerUUID
     *
     * @return string
     */
    public function getBannerUUID()
    {
        return $this->bannerUUID;
    }

    /**
     * Set admin
     *
     * @param \DGIModule\Entity\Administration $admin
     *
     * @return Banner
     */
    public function setAdmin(\DGIModule\Entity\Administration $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \DGIModule\Entity\Administration
     */
    public function getAdmin()
    {
        return $this->admin;
    }
    
    /**
     * Set bannerLevel
     *
     * @param integer $bannerLevel
     *
     * @return DgiBanners
     */
    public function setBannerLevel($bannerLevel)
    {
        $this->bannerLevel = $bannerLevel;
    
        return $this;
    }
    
    /**
     * Get bannerLevel
     *
     * @return integer
     */
    public function getBannerLevel()
    {
        return $this->bannerLevel;
    }
    

    /**
     * Set bannerFullCity
     *
     * @param integer $bannerFullCity
     *
     * @return DgiBanners
     */
    public function setBannerFullCity($bannerFullCity)
    {
        $this->bannerFullCity = $bannerFullCity;
    
        return $this;
    }
    
    /**
     * Get bannerFullCity
     *
     * @return integer
     */
    public function getBannerFullCity()
    {
        return $this->bannerFullCity;
    }
    
    
    /**
     * Set city
     *
     * @param \DGIModule\Entity\City $city
     *
     * @return DgiBanners
     */
    public function setCity(\DGIModule\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }
    
    /**
     * Get city
     *
     * @return \Import\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }
}
