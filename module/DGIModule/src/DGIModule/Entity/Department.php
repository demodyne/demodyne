<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Department
 *
 * @ORM\Table(name="dgi_departments", indexes={@ORM\Index(name="code", columns={"dep_code"}), @ORM\Index(name="region_fk_idx", columns={"reg_id"}), @ORM\Index(name="country_fk_idx", columns={"country_id"})})
 * @ORM\Entity
 */
class Department
{
    /**
     * @var integer
     *
     * @ORM\Column(name="dep_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $depId;

    /**
     * @var string
     *
     * @ORM\Column(name="dep_code", type="string", length=3, nullable=false)
     */
    private $depCode;

    /**
     * @var string
     *
     * @ORM\Column(name="dep_name", type="string", length=250, nullable=false)
     */
    private $depName;

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
     * @var \DGIModule\Entity\Region
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reg_id", referencedColumnName="region_id")
     * })
     */
    private $reg;
    
    /**
     * @var \Doctrine\Common\Collections\Collection|Partners[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Partner", mappedBy="departments", cascade={"persist", "merge", "remove"})
     */
    private $partners;



    /**
     * Get depId
     *
     * @return integer 
     */
    public function getDepId()
    {
        return $this->depId;
    }

    /**
     * Set depCode
     *
     * @param string $depCode
     * @return Department
     */
    public function setDepCode($depCode)
    {
        $this->depCode = $depCode;

        return $this;
    }

    /**
     * Get depCode
     *
     * @return string 
     */
    public function getDepCode()
    {
        return $this->depCode;
    }

    /**
     * Set depName
     *
     * @param string $depName
     * @return Department
     */
    public function setDepName($depName)
    {
        $this->depName = $depName;

        return $this;
    }

    /**
     * Get depName
     *
     * @return string 
     */
    public function getDepName()
    {
        return $this->depName;
    }

    /**
     * Set country
     *
     * @param \DGIModule\Entity\Country $country
     * @return Department
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
     * Set reg
     *
     * @param \DGIModule\Entity\Region $reg
     * @return Department
     */
    public function setRegion(\DGIModule\Entity\Region $reg = null)
    {
        $this->reg = $reg;

        return $this;
    }

    /**
     * Get reg
     *
     * @return \DGIModule\Entity\Region 
     */
    public function getRegion()
    {
        return $this->reg;
    }
}
