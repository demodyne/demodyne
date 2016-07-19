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
 * History
 *
 * @ORM\Table(name="dgi_history", indexes={@ORM\Index(name="his_usr_fk_idx", columns={"usr_id"})})
 * @ORM\Entity
 */
class History
{
    /**
     * @var integer
     *
     * @ORM\Column(name="his_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $hisId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="his_type", type="integer", nullable=true)
     */
    private $hisType = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="his_created_date", type="datetime", nullable=true)
     */
    private $hisCreatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="his_name", type="string", length=100, nullable=true)
     */
    private $hisName;

    /**
     * @var string
     *
     * @ORM\Column(name="his_description", type="string", length=2000, nullable=true)
     */
    private $hisDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="his_start_date", type="datetime", nullable=true)
     */
    private $hisStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="his_end_date", type="datetime", nullable=true)
     */
    private $hisEndDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="his_cost", type="integer", nullable=true)
     */
    private $hisCost;

    /**
     * @var \Doctrine\Common\Collections\Collection|Link[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Link", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_history_links",
     *  joinColumns={
     *      @ORM\JoinColumn(name="his_id", referencedColumnName="his_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="link_id", referencedColumnName="link_id")
     *  }
     * )
     */
    private $links;

    /**
     * @var \DGIModule\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="his_category", referencedColumnName="cat_id")
     * })
     */
    private $category;

    /**
     * @var \DGIModule\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;

    public function __construct()
    {
        $this->links = new ArrayCollection();
    }
    
    /**
     * Set hisType
     *
     * @param integer $hisType
     *
     * @return History
     */
    public function setHisType($hisType)
    {
        $this->hisType = $hisType;
    
        return $this;
    }
    
    /**
     * Get hisType
     *
     * @return integer
     */
    public function getHisType()
    {
        return $this->hisType;
    }
    
    
    public function getLinks()
    {
        $urls = [];
        foreach ($this->links as $link) {
            $urls[] = $link->getLinkUrl();
        }
    
        return $urls;
    }
    
    public function getFullLinks()
    {
        return $this->links;
    }
    
    /**
     * @param Link $link
     */
    public function addLink($url, $added = 1)
    {
    
        $this->links->add(new Link($url, $added));
    }
    
    /**
     * @param Link $link
     */
    public function removeLink(Link $link)
    {
        if (!$this->links->contains($link)) {
            return;
        }
    
        $this->links->removeElement($link);
    
    }
    
    public function clearLinks() {
        $this->links->clear();
    }
    

    /**
     * Get hisId
     *
     * @return integer
     */
    public function getHisId()
    {
        return $this->hisId;
    }

    /**
     * Set hisCreatedDate
     *
     * @param \DateTime $hisCreatedDate
     *
     * @return History
     */
    public function setHisCreatedDate($hisCreatedDate)
    {
        $this->hisCreatedDate = $hisCreatedDate;

        return $this;
    }

    /**
     * Get hisCreatedDate
     *
     * @return \DateTime
     */
    public function getHisCreatedDate()
    {
        return $this->hisCreatedDate;
    }

   /**
     * Set hisName
     *
     * @param string $hisName
     *
     * @return History
     */
    public function setHisName($hisName)
    {
        $this->hisName = $hisName;

        return $this;
    }

    /**
     * Get hisName
     *
     * @return string
     */
    public function getHisName()
    {
        return $this->hisName;
    }

    /**
     * Set hisDescription
     *
     * @param integer $hisDescription
     *
     * @return History
     */
    public function setHisDescription($hisDescription)
    {
        $this->hisDescription = $hisDescription;

        return $this;
    }

    /**
     * Get hisDescription
     *
     * @return integer
     */
    public function getHisDescription()
    {
        return $this->hisDescription;
    }

    /**
     * Set hisStartDate
     *
     * @param integer $hisStartDate
     *
     * @return History
     */
    public function setHisStartDate($hisStartDate)
    {
        $this->hisStartDate = $hisStartDate;

        return $this;
    }

    /**
     * Get hisStartDate
     *
     * @return integer
     */
    public function getHisStartDate()
    {
        return $this->hisStartDate;
    }

    /**
     * Set hisEndDate
     *
     * @param integer $hisEndDate
     *
     * @return History
     */
    public function setHisEndDate($hisEndDate)
    {
        $this->hisEndDate = $hisEndDate;

        return $this;
    }

    /**
     * Get hisEndDate
     *
     * @return integer
     */
    public function getHisEndDate()
    {
        return $this->hisEndDate;
    }

    /**
     * Set hisCost
     *
     * @param integer $hisCost
     *
     * @return History
     */
    public function setHisCost($hisCost)
    {
        $this->hisCost = $hisCost;

        return $this;
    }

    /**
     * Get hisCost
     *
     * @return integer
     */
    public function getHisCost()
    {
        return $this->hisCost;
    }

   /**
     * Set category
     *
     * @param \DGIModule\Entity\Category $category
     *
     * @return History
     */
    public function setCategory(\DGIModule\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \DGIModule\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set usr
     *
     * @param \DGIModule\Entity\User $usr
     *
     * @return History
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
