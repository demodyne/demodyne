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
 * Partner
 *
 * @ORM\Table(name="dgi_partners")
 * @ORM\Entity
 */
class Partner
{
    /**
     * @var integer
     *
     * @ORM\Column(name="part_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $partId;

    /**
     * @var string
     *
     * @ORM\Column(name="part_name", type="string", length=100, nullable=false)
     */
    private $partName;

    /**
     * @var string
     *
     * @ORM\Column(name="part_siret", type="string", length=45, nullable=false)
     */
    private $partSiret;

    /**
     * @var string
     *
     * @ORM\Column(name="part_address", type="string", length=1000, nullable=false)
     */
    private $partAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="part_employees", type="integer", nullable=true)
     */
    private $partEmployees;

        /**
     * @var string
     *
     * @ORM\Column(name="part_presentation", type="string", length=1000, nullable=false)
     */
    private $partPresentation;

    /**
     * @var integer
     *
     * @ORM\Column(name="part_gain", type="integer", nullable=false)
     */
    private $partGain;

    /**
     * @var string
     *
     * @ORM\Column(name="part_website", type="string", length=500, nullable=true)
     */
    private $partWebsite;

    /**
     * @var string
     *
     * @ORM\Column(name="part_fax", type="string", length=12, nullable=true)
     */
    private $partFax;
    
    /**
     * @var string
     *
     * @ORM\Column(name="part_keywords", type="string", length=1000, nullable=true)
     */
    private $partKeywords = '';
    
    /**
     * @var \Doctrine\Common\Collections\Collection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Category", inversedBy="partners", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_partners_categories",
     *  joinColumns={
     *      @ORM\JoinColumn(name="part_id", referencedColumnName="part_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     *  }
     * )
     */
     private $categories;
     
     /**
      * @var \Doctrine\Common\Collections\Collection|Department[]
      *
      * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Department", inversedBy="partners", cascade={"persist", "merge","remove"})
      * @ORM\JoinTable(
      *  name="dgi_partners_departments",
      *  joinColumns={
      *      @ORM\JoinColumn(name="part_id", referencedColumnName="part_id")
      *  },
      *  inverseJoinColumns={
      *      @ORM\JoinColumn(name="dep_id", referencedColumnName="dep_id")
      *  }
      * )
      */
     private $departments;
     
     public function __construct()
     {
         $this->categories = new ArrayCollection();
         $this->departments = new ArrayCollection();
     }

    /**
     * Get partId
     *
     * @return integer 
     */
    public function getPartId()
    {
        return $this->partId;
    }

    /**
     * Set partName
     *
     * @param string $partName
     * @return Partner
     */
    public function setPartName($partName)
    {
        $this->partName = $partName;

        return $this;
    }

    /**
     * Get partName
     *
     * @return string 
     */
    public function getPartName()
    {
        return $this->partName;
    }

    /**
     * Set partSiret
     *
     * @param string $partSiret
     * @return Partner
     */
    public function setPartSiret($partSiret)
    {
        $this->partSiret = $partSiret;

        return $this;
    }

    /**
     * Get partSiret
     *
     * @return string 
     */
    public function getPartSiret()
    {
        return $this->partSiret;
    }

    /**
     * Set partAddress
     *
     * @param string $partAddress
     * @return Partner
     */
    public function setPartAddress($partAddress)
    {
        $this->partAddress = $partAddress;

        return $this;
    }

    /**
     * Get partAddress
     *
     * @return string 
     */
    public function getPartAddress()
    {
        return $this->partAddress;
    }

    /**
     * Set partEmployees
     *
     * @param integer $partEmployees
     * @return Partner
     */
    public function setPartEmployees($partEmployees)
    {
        $this->partEmployees = $partEmployees;

        return $this;
    }

    /**
     * Get partEmployees
     *
     * @return integer 
     */
    public function getPartEmployees()
    {
        return $this->partEmployees;
    }

       /**
     * Set partPresentation
     *
     * @param string $partPresentation
     * @return Partner
     */
    public function setPartPresentation($partPresentation)
    {
        $this->partPresentation = $partPresentation;

        return $this;
    }

    /**
     * Get partPresentation
     *
     * @return string 
     */
    public function getPartPresentation()
    {
        return $this->partPresentation;
    }

    /**
     * Set partGain
     *
     * @param integer $partGain
     * @return Partner
     */
    public function setPartGain($partGain)
    {
        $this->partGain = $partGain;

        return $this;
    }

    /**
     * Get partGain
     *
     * @return integer 
     */
    public function getPartGain()
    {
        return $this->partGain;
    }

    /**
     * Set partWebsite
     *
     * @param string $partWebsite
     * @return Partner
     */
    public function setPartWebsite($partWebsite)
    {
        $this->partWebsite = $partWebsite;

        return $this;
    }

    /**
     * Get partWebsite
     *
     * @return string 
     */
    public function getPartWebsite()
    {
        return $this->partWebsite;
    }

    /**
     * Set partFax
     *
     * @param string $partFax
     * @return Partner
     */
    public function setPartFax($partFax)
    {
        $this->partFax = $partFax;

        return $this;
    }

    /**
     * Get partFax
     *
     * @return string 
     */
    public function getPartFax()
    {
        return $this->partFax;
    }
    
    public function getCategories() {
        return $this->categories;
    }
    
    public function addCategory(Category $category)
    {
        if ($this->categories->contains($category)) {
            return;
        }
    
        $this->categories->add($category);
        
        return $this;
    }
    
    public function removeCategory(Category $category)
    {
        if (!$this->categories->contains($category)) {
            return;
        }
    
        $this->categories->remove($category);
        return $this;
    }
    
    public function resetCategories()
    {
        $this->categories->clear();
        return $this;
    }
    
    /**
     * Departments section
     * @return \Doctrine\Common\Collections\Collection
     */
    
    public function getDepartments() {
        return $this->departments;
    }
    
    public function addDepartment(Department $department)
    {
        if ($this->departments->contains($department)) {
            return;
        }
    
        $this->departments->add($department);
    
        return $this;
    }
    
    public function removeDepartment(Department $department)
    {
        if (!$this->departments->contains($department)) {
            return;
        }
    
        $this->departments->remove($department);
        return $this;
    }
    
    public function resetDepartments()
    {
        $this->departments->clear();
        return $this;
    }
    
    /**
     * Set partKeywords
     *
     * @param string $partKeywords
     *
     * @return Partner
     */
    public function setPartKeywords($partKeywords)
    {
        $this->partKeywords = $partKeywords;
    
        return $this;
    }
    
    /**
     * Get partKeywords
     *
     * @return string
     */
    public function getPartKeywords()
    {
        return $this->partKeywords;
    }
    
}
