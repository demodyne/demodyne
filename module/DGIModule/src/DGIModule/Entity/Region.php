<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use DGIModule\Entity\Country;
use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="dgi_regions", indexes={@ORM\Index(name="id_country_reg_fk_idx", columns={"country_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\RegionRepository")
 */
class Region
{
    /**
     * @var integer
     *
     * @ORM\Column(name="region_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $regionId;

    /**
     * @var string
     *
     * @ORM\Column(name="region_name", type="string", length=250, nullable=false)
     */
    private $regionName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="region_code", type="string", length=20, nullable=true)
     */
    private $regionCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="region_sub", type="string", length=500, nullable=false)
     */
    private $regionSub;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="region_population", type="integer", nullable=true)
     */
    private $regionPopulation = 0;
    
    /**
     * @var string
     *
     * @ORM\Column(name="region_timezone", type="string", length=45, nullable=true)
     */
    private $regionTimezone = 'UTC';
    

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="\DGIModule\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;
    
    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Department", mappedBy="reg") */
    private $departments;

    /**
     * Get regionId
     *
     * @return integer 
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * Set regionName
     *
     * @param string $regionName
     * @return Region
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
     * Get regionName
     *
     * @return string
     */
    public function getRegionNameWithState()
    {

        return $this->country->getRegionName()?$this->country->getRegionName().' '.$this->regionName:$this->regionName;
    }

    /**
     * Set regionName
     *
     * @param string $regionSub
     * @return Region
     */
    public function setRegionSub($regionSub)
    {
        $this->regionSub = $regionSub;
    
        return $this;
    }
    
    /**
     * Get regionName
     *
     * @return string
     */
    public function getRegionSub()
    {
    
        return $this->regionSub;
    }
    
    public function getRegionNameSolo() {
        return $this->regionName;
    }
    
    /**
     * Set regionCode
     *
     * @param string $regionCode
     *
     * @return Region
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;
    
        return $this;
    }
    
    /**
     * Get regionCode
     *
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }
    
    /**
     * Set regionPopulation
     *
     * @param integer $regionPopulation
     *
     * @return Region
     */
    public function setRegionPopulation($regionPopulation)
    {
        $this->regionPopulation = $regionPopulation;
    
        return $this;
    }
    
    /**
     * Get regionPopulation
     *
     * @return integer
     */
    public function getRegionPopulation()
    {
        return $this->regionPopulation;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return Region
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    public function getDepartments() {
        return $this->departments;
    }
    /**
     * @return string $regionTimezone
     */
    public function getRegionTimezone()
    {
        return $this->regionTimezone;
    }

}
