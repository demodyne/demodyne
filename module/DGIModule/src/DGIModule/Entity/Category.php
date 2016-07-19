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
 * Category
 *
 * @ORM\Table(name="dgi_categories", indexes={@ORM\Index(name="id_category_fk_cat_idx", columns={"cat_id_cat"}), @ORM\Index(name="country_category_fk_idx", columns={"country_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cat_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $catId;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_name", type="string", length=50, nullable=false)
     */
    private $catName;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_description", type="string", length=2000, nullable=false)
     */
    private $catDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cat_image", type="string", length=256, nullable=false)
     */
    private $catImage;

    /**
     * @var \DGIModule\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id_cat", referencedColumnName="cat_id")
     * })
     */
    private $catCat;
    
    /**
     * @var DGIModule\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cat_city", type="integer", nullable=true)
     */
    private $catCity = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cat_region", type="integer", nullable=true)
     * 
     */
    private $catRegion = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cat_country", type="integer", nullable=true)
     */
    private $catCountry = 1;
    
    /**
     * @var \Doctrine\Common\Collections\Collection|Partners[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Partner", mappedBy="categories", cascade={"persist", "merge"})
     */
     private $partners;

     /**
      * @var \Doctrine\Common\Collections\Collection|Category[]
      *
      * @ORM\OneToMany(targetEntity="DGIModule\Entity\Category", mappedBy="catCat",  cascade={"persist", "merge", "remove"})
      */
     private $subCategories;
     
     public function __construct() {
         $this->subCategories = new ArrayCollection();
     }

    /**
     * Get catId
     *
     * @return integer 
     */
    public function getCatId()
    {
        return $this->catId;
    }

    /**
     * Set catName
     *
     * @param string $catName
     * @return Category
     */
    public function setCatName($catName)
    {
        $this->catName = $catName;

        return $this;
    }

    /**
     * Get catName
     *
     * @return string 
     */
    public function getCatName()
    {
        return $this->catName;
    }

    /**
     * Set catDescription
     *
     * @param string $catDescription
     * @return Category
     */
    public function setCatDescription($catDescription)
    {
        $this->catDescription = $catDescription;

        return $this;
    }

    /**
     * Get catDescription
     *
     * @return string 
     */
    public function getCatDescription()
    {
        return $this->catDescription;
    }

    /**
     * Set catImage
     *
     * @param string $catImage
     * @return Category
     */
    public function setCatImage($catImage)
    {
        $this->catImage = $catImage;

        return $this;
    }

    /**
     * Get catImage
     *
     * @return string 
     */
    public function getCatImage()
    {
        return $this->catImage;
    }
    
    /**
     * Set catCity
     *
     * @param integer $catCity
     *
     * @return Category
     */
    public function setCatCity($catCity)
    {
        $this->catCity = $catCity;
    
        return $this;
    }
    
    /**
     * Get catCity
     *
     * @return integer
     */
    public function getCatCity()
    {
        return $this->catCity;
    }
    
    /**
     * Set catRegion
     *
     * @param integer $catRegion
     *
     * @return Category
     */
    public function setCatRegion($catRegion)
    {
        $this->catRegion = $catRegion;
    
        return $this;
    }
    
    /**
     * Get catRegion
     *
     * @return integer
     */
    public function getCatRegion()
    {
        return $this->catRegion;
    }
    
    /**
     * Set catCountry
     *
     * @param integer $catCountry
     *
     * @return Category
     */
    public function setCatCountry($catCountry)
    {
        $this->catCountry = $catCountry;
    
        return $this;
    }
    
    /**
     * Get catCountry
     *
     * @return integer
     */
    public function getCatCountry()
    {
        return $this->catCountry;
    }

    /**
     * Set catCat
     *
     * @param \DGIModule\Entity\Category $catCat
     * @return Category
     */
    public function setCatCat(\DGIModule\Entity\Category $catCat = null)
    {
        $this->catCat = $catCat;

        return $this;
    }

    /**
     * Get catCat
     *
     * @return \DGIModule\Entity\Category 
     */
    public function getCatCat()
    {
        return $this->catCat;
    }
    
    /**
     * Set country
     *
     * @param \DGIModule\Entity\Country $country
     * @return Category
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
    
    public function getSubCategories() {
        return $this->subCategories;
    }
    
    public function addCategory(Category $category) {
        $category->setCatCat($this);
        
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->subCategories->contains($category)) {
            $this->subCategories->add($category);
        }
    }
    
    public function removeCategory(Category $category) {
        //$category->setCatCat($this);
    
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->subCategories->contains($category)) {
            $this->subCategories->remove($category);
        }
    }
    
    public function setSubCategories($subCategories) {
        $this->subCategories = $subCategories;
        return $this;
    }
}
