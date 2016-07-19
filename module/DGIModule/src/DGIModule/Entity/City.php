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
 * City
 *
 * @ORM\Table(name="dgi_cities", indexes={@ORM\Index(name="city_country_fk_idx", columns={"country_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\CityRepository")
 */
class City
{
    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cityId;

    /**
     * @var string
     *
     * @ORM\Column(name="city_postalcode", type="string", length=20, nullable=false)
     */
    private $cityPostalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city_name", type="string", length=180, nullable=false)
     */
    private $cityName;

    /**
     * @var string
     *
     * @ORM\Column(name="state_name", type="string", length=100, nullable=true)
     */
    private $stateName;

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=20, nullable=true)
     */
    private $stateCode = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="county_name", type="string", length=100, nullable=true)
     */
    private $countyName;

    /**
     * @var string
     *
     * @ORM\Column(name="county_code", type="string", length=20, nullable=true)
     */
    private $countyCode = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="community_name", type="string", length=100, nullable=true)
     */
    private $communityName;

    /**
     * @var string
     *
     * @ORM\Column(name="community_code", type="string", length=20, nullable=true)
     */
    private $communityCode = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="city_latitude", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $cityLatitude = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="city_longitude", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $cityLongitude = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="accuracy", type="integer", nullable=true)
     */
    private $accuracy = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_population", type="integer", nullable=true)
     */
    private $cityPopulation = 0;
    
    /**
     * @var string
     *
     * @ORM\Column(name="district_name", type="string", length=100, nullable=true)
     */
    private $districtName;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="district_code", type="integer", nullable=true)
     */
    private $districtCode = '0';

    /**
     * @var \DGIModule\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;
    
    /**
     * @var \DGIModule\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="full_city_id", referencedColumnName="city_id")
     * })
     */
    private $fullCity;
    
    /**
     * @var \DGIModule\Entity\City
     *
     * @ORM\OneToMany(targetEntity="DGIModule\Entity\City", mappedBy="fullCity")
     */
    private $districts;
    
    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Proposal", mappedBy="city") */
    private $proposals;

    /**
     * @var \DGIModule\Entity\Region
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     * })
     */
    private $region;

    
    public function __construct() {
        $this->districts = new ArrayCollection();
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set cityPostalcode
     *
     * @param string $cityPostalcode
     *
     * @return City
     */
    public function setCityPostalcode($cityPostalcode)
    {
        $this->cityPostalcode = $cityPostalcode;

        return $this;
    }

    /**
     * Get cityPostalcode
     *
     * @return string
     */
    public function getCityPostalcode()
    {
        return $this->cityPostalcode;
    }

    /**
     * Set cityName
     *
     * @param string $cityName
     *
     * @return City
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName
     *
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set stateName
     *
     * @param string $stateName
     *
     * @return City
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;

        return $this;
    }

    /**
     * Get stateName
     *
     * @return string
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return City
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * Get stateCode
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }

    /**
     * Set countyName
     *
     * @param string $countyName
     *
     * @return City
     */
    public function setCountyName($countyName)
    {
        $this->countyName = $countyName;

        return $this;
    }

    /**
     * Get countyName
     *
     * @return string
     */
    public function getCountyName()
    {
        return $this->countyName;
    }

    /**
     * Set countyCode
     *
     * @param string $countyCode
     *
     * @return City
     */
    public function setCountyCode($countyCode)
    {
        $this->countyCode = $countyCode;

        return $this;
    }

    /**
     * Get countyCode
     *
     * @return string
     */
    public function getCountyCode()
    {
        return $this->countyCode;
    }

    /**
     * Set communityName
     *
     * @param string $communityName
     *
     * @return City
     */
    public function setCommunityName($communityName)
    {
        $this->communityName = $communityName;

        return $this;
    }

    /**
     * Get communityName
     *
     * @return string
     */
    public function getCommunityName()
    {
        return $this->communityName;
    }

    /**
     * Set communityCode
     *
     * @param string $communityCode
     *
     * @return City
     */
    public function setCommunityCode($communityCode)
    {
        $this->communityCode = $communityCode;

        return $this;
    }

    /**
     * Get communityCode
     *
     * @return string
     */
    public function getCommunityCode()
    {
        return $this->communityCode;
    }

    /**
     * Set cityLatitude
     *
     * @param string $cityLatitude
     *
     * @return City
     */
    public function setCityLatitude($cityLatitude)
    {
        $this->cityLatitude = $cityLatitude;

        return $this;
    }

    /**
     * Get cityLatitude
     *
     * @return string
     */
    public function getCityLatitude()
    {
        return $this->cityLatitude;
    }

    /**
     * Set cityLongitude
     *
     * @param string $cityLongitude
     *
     * @return City
     */
    public function setCityLongitude($cityLongitude)
    {
        $this->cityLongitude = $cityLongitude;

        return $this;
    }

    /**
     * Get cityLongitude
     *
     * @return string
     */
    public function getCityLongitude()
    {
        return $this->cityLongitude;
    }

    /**
     * Set accuracy
     *
     * @param integer $accuracy
     *
     * @return City
     */
    public function setAccuracy($accuracy)
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    /**
     * Get accuracy
     *
     * @return integer
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * Set cityPopulation
     *
     * @param integer $cityPopulation
     *
     * @return City
     */
    public function setCityPopulation($cityPopulation)
    {
        $this->cityPopulation = $cityPopulation;

        return $this;
    }

    /**
     * Get cityPopulation
     *
     * @return integer
     */
    public function getCityPopulation()
    {
        return $this->cityPopulation;
    }
    
    /**
     * Set districtName
     *
     * @param string $districtName
     *
     * @return City
     */
    public function setDistrictName($districtName)
    {
        $this->districtName = $districtName;
    
        return $this;
    }
    
    /**
     * Get districtName
     *
     * @return string
     */
    public function getDistrictName()
    {
        return $this->districtName;
    }
    
    /**
     * Set districtCode
     *
     * @param integer $districtCode
     *
     * @return City
     */
    public function setDistrictCode($districtCode)
    {
        $this->districtCode = $districtCode;
    
        return $this;
    }
    
    /**
     * Get districtCode
     *
     * @return integer
     */
    public function getDistrictCode()
    {
        return $this->districtCode;
    }

    /**
     * Set country
     *
     * @param \DGIModule\Entity\Country $country
     *
     * @return City
     */
    public function setCountry(\DGIModule\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \DGIModule\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * Set fullCity
     *
     * @param \DGIModule\Entity\City $fullCity
     *
     * @return City
     */
    public function setFullCity(\DGIModule\Entity\City $fullCity = null)
    {
        $this->fullCity = $fullCity;
    
        return $this;
    }
    
    /**
     * Get fullCity
     *
     * @return \DGIModule\Entity\City
     */
    public function getFullCity()
    {
        return $this->fullCity;
    }
    
    public function isFullCity() {
        return count($this->districts)>0;
    }
    
    /**
     * Set region
     *
     * @param \DGIModule\Entity\Region $region
     *
     * @return DgiCities
     */
    public function setRegion(\DGIModule\Entity\Region $region = null)
    {
        $this->region = $region;
    
        return $this;
    }
    
    /**
     * Get region
     *
     * @return \DGIModule\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }
}
