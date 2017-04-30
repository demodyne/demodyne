<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Country
 *
 * @ORM\Table(name="dgi_countries")
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\CountryRepository")
 */
class Country
{
    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $countryId;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=2, nullable=false)
     */
    private $countryCode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=100, nullable=false)
     */
    private $countryName = '';
    
    /**
     * @var string
     *
     * @ORM\Column(name="country_format", type="string", length=5, nullable=false)
     */
    private $countryFormat = '';
    
    /**
     * @var string
     *
     * @ORM\Column(name="country_region_name", type="string", length=45, nullable=true)
     */
    private $regionName = null;
    
    /**
     * @var string
     *
     * @ORM\Column(name="country_currency", type="string", length=4, nullable=true)
     */
    private $countryCurrency = '';
    
    /**
     * @var string
     *
     * @ORM\Column(name="country_other_category", type="string", length=100, nullable=true)
     */
    private $countryOtherCategory = '';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="country_postalcode", type="integer", nullable=true)
     */
    private $countryPostalcode = 5;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="country_activated", type="integer", nullable=true)
     */
    private $countryActivated = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="country_population", type="integer", nullable=true)
     */
    private $countryPopulation = 0;
    
    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Banner", mappedBy="country") */
    private $banners;
    
    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Category", mappedBy="country") */
    private $categories;

    public function __construct() {
        $this->categories = new ArrayCollection();
    }


    /**
     * Get countryId
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return Country
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     * @return Country
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string 
     */
    public function getCountryName()
    {
        return $this->countryName;
    }
    
    /**
     * Set regionName
     *
     * @param string $regionName
     * @return Country
     */
    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;
    
        return $this;
    }
    
    /**
     * Get regionName
     *
     * @return string
     */
    public function getRegionName()
    {
        return $this->regionName;
    }
    
    /**
     * Get countryCurrency
     *
     * @return string
     */
    public function getCountryCurrency()
    {
        return $this->countryCurrency;
    }
    
    /**
     * Set countryOtherCategory
     *
     * @param string $countryOtherCategory
     *
     * @return Country
     */
    public function setCountryOtherCategory($countryOtherCategory)
    {
        $this->countryOtherCategory = $countryOtherCategory;
    
        return $this;
    }
    
    /**
     * Get countryOtherCategory
     *
     * @return string
     */
    public function getCountryOtherCategory()
    {
        return $this->countryOtherCategory;
    }
    
    /**
     * Set countryActivated
     *
     * @param integer $countryActivated
     *
     * @return Country
     */
    public function setCountryActivated($countryActivated)
    {
        $this->countryActivated = $countryActivated;
    
        return $this;
    }
    
    /**
     * Get countryActivated
     *
     * @return integer
     */
    public function getCountryActivated()
    {
        return $this->countryActivated;
    }
    
    /**
     * Set countryPopulation
     *
     * @param integer $countryPopulation
     *
     * @return Country
     */
    public function setCountryPopulation($countryPopulation)
    {
        $this->countryPopulation = $countryPopulation;
    
        return $this;
    }
    
    /**
     * Get countryPopulation
     *
     * @return integer
     */
    public function getCountryPopulation()
    {
        return $this->countryPopulation;
    }
    
    public function getBanners() {
        return $this->banners;
    }
    
    public function getLevelBanners($level) {
        $banners= [];
        /** @var \DGIModule\Entity\Banner $banner */
        foreach ($this->banners as $banner) {
            if ($banner->getBannerLevel()==$level) {
                $banners[] = $banner;
            }
        }
        return $banners;
    }

    /**
     * @param Category $a
     * @param Category $b
     * @return int
     */
    private static function compareCategoriesByName($a, $b)
    {
        if (strtolower($a->getCatName()) == strtolower($b->getCatName())) {
            return 0;
        }
        return (strtolower($a->getCatName()) < strtolower($b->getCatName())) ? -1 : 1;
    }
    
    public function getMainCategories($level=null) {
        $mainCategories = [];
        foreach ($this->categories as $category) {
            $levels = [
                1 => $category->getCatCity(), 
                2 => $category->getCatRegion(), 
                3 => $category->getCatCountry()
            ];
            if (!$category->getCatCat() && (!$level || $levels[$level])) {
                $mainCategories[] = $category;
            }
        }
        usort($mainCategories, array('\DGIModule\Entity\Country','compareCategoriesByName'));
        return $mainCategories;
    }
    /**
     * @return string $countryPostalcode
     */
    public function getCountryPostalcode()
    {
        return $this->countryPostalcode;
    }
    /**
     * @return string $countryFormat
     */
    public function getCountryFormat()
    {
        return $this->countryFormat;
    }


}
