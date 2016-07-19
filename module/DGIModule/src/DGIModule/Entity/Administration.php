<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Administration
 *
 * @ORM\Table(name="dgi_administration", indexes={@ORM\Index(name="fk_admin_city_idx", columns={"admin_city"}), @ORM\Index(name="fk_admin_region_idx", columns={"admin_region"})})
 * @ORM\Entity
 */
class Administration
{
    /**
     * @var integer
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminId;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_name", type="string", length=100, nullable=false)
     */
    private $adminName;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_presentation", type="string", length=1000, nullable=false)
     */
    private $adminPresentation;

    /**
     * @var integer
     *
     * @ORM\Column(name="admin_level", type="integer", nullable=false)
     */
    private $adminLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_address", type="string", length=200, nullable=false)
     */
    private $adminAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_fax", type="string", length=12, nullable=true)
     */
    private $adminFax;
    
    /**
     * @var string
     *
     * @ORM\Column(name="admin_website", type="string", length=500, nullable=false)
     */
    private $adminWebsite = '';

    /**
     * @var \DGIModule\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_city", referencedColumnName="city_id")
     * })
     */
    private $adminCity;

    /**
     * @var \DGIModule\Entity\Region
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_region", referencedColumnName="region_id")
     * })
     */
    private $adminRegion;
   
    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Banner", mappedBy="admin") */
    private $banners;
    
    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Newsletter", mappedBy="admin") */
    private $newsletters;
    
    /**
     * @var \DGIModule\Entity\User
     *
     * @ORM\OneToOne(targetEntity="DGIModule\Entity\User", mappedBy="admin", cascade={"persist", "merge", "remove"})
     */
    private $user;

    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->banners = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
    }
    
    
    public function getBanners() {
        return $this->banners;
    }
    
    public function getActiveBanners() {
        $b = [];
        foreach ($this->banners as $banner) {
            if ($banner->getBannerPublished()) {
                $b[] = $banner;
            }
        }
        return $b;
    }
    
    
    public function getNewsletters() {
        return $this->newsletters;
    }

    /**
     * Get adminId
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set adminName
     *
     * @param string $adminName
     *
     * @return Administration
     */
    public function setAdminName($adminName)
    {
        $this->adminName = $adminName;

        return $this;
    }

    /**
     * Get adminName
     *
     * @return string
     */
    public function getAdminName()
    {
        return $this->adminName;
    }

    /**
     * Set adminPresentation
     *
     * @param string $adminPresentation
     *
     * @return Administration
     */
    public function setAdminPresentation($adminPresentation)
    {
        $this->adminPresentation = $adminPresentation;

        return $this;
    }

    /**
     * Get adminPresentation
     *
     * @return string
     */
    public function getAdminPresentation()
    {
        return $this->adminPresentation;
    }

    /**
     * Set adminLevel
     *
     * @param integer $adminLevel
     *
     * @return Administration
     */
    public function setAdminLevel($adminLevel)
    {
        $this->adminLevel = $adminLevel;

        return $this;
    }

    /**
     * Get adminLevel
     *
     * @return integer
     */
    public function getAdminLevel()
    {
        return $this->adminLevel;
    }

    /**
     * Set adminAddress
     *
     * @param string $adminAddress
     *
     * @return Administration
     */
    public function setAdminAddress($adminAddress)
    {
        $this->adminAddress = $adminAddress;

        return $this;
    }

    /**
     * Get adminAddress
     *
     * @return string
     */
    public function getAdminAddress()
    {
        return $this->adminAddress;
    }

    /**
     * Set adminFax
     *
     * @param string $adminFax
     *
     * @return Administration
     */
    public function setAdminFax($adminFax)
    {
        $this->adminFax = $adminFax;

        return $this;
    }

    /**
     * Get adminFax
     *
     * @return string
     */
    public function getAdminFax()
    {
        return $this->adminFax;
    }
    
    /**
     * Set adminWebsite
     *
     * @param string $adminWebsite
     *
     * @return Administration
     */
    public function setAdminWebsite($adminWebsite)
    {
        $this->adminWebsite = $adminWebsite;
    
        return $this;
    }
    
    /**
     * Get adminWebsite
     *
     * @return string
     */
    public function getAdminWebsite()
    {
        return $this->adminWebsite;
    }
    

    /**
     * Set adminCity
     *
     * @param \DGIModule\Entity\City $adminCity
     *
     * @return Administration
     */
    public function setAdminCity(\DGIModule\Entity\City $adminCity = null)
    {
        $this->adminCity = $adminCity;

        return $this;
    }

    /**
     * Get adminCity
     *
     * @return \DGIModule\Entity\City
     */
    public function getAdminCity()
    {
        return $this->adminCity;
    }

    /**
     * Set adminRegion
     *
     * @param \DGIModule\Entity\Region $adminRegion
     *
     * @return Administration
     */
    public function setAdminRegion(\DGIModule\Entity\Region $adminRegion = null)
    {
        $this->adminRegion = $adminRegion;

        return $this;
    }

    /**
     * Get adminRegion
     *
     * @return \DGIModule\Entity\Region
     */
    public function getAdminRegion()
    {
        return $this->adminRegion;
    }
    
    /**
     * Get adminRegion
     *
     * @return \DGIModule\Entity\Region
     */
    public function getUser()
    {
        return $this->user;
    }
}
