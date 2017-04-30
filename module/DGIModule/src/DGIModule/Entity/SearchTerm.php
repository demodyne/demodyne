<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use DGIModule\Entity\Category;
use DGIModule\Entity\Department;
use DGIModule\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * SearchTerm
 *
 * @ORM\Table(name="dgi_search_terms", indexes={@ORM\Index(name="src_fk_usr_idx", columns={"usr_id"}), @ORM\Index(name="src_fk_dep_idx", columns={"dep_id"}), @ORM\Index(name="src_fk_cat_idx", columns={"cat_id"})})
 * @ORM\Entity
 */
class SearchTerm
{
    /**
     * @var integer
     *
     * @ORM\Column(name="search_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $searchId;

    /**
     * @var string
     *
     * @ORM\Column(name="search_keywords", type="string", length=1000, nullable=true)
     */
    private $searchKeywords;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     * })
     */
    private $cat;

    /**
     * @var Department
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Department")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dep_id", referencedColumnName="dep_id")
     * })
     */
    private $dep;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;



    /**
     * Get searchId
     *
     * @return integer
     */
    public function getSearchId()
    {
        return $this->searchId;
    }

    /**
     * Set searchKeywords
     *
     * @param string $searchKeywords
     *
     * @return SearchTerm
     */
    public function setSearchKeywords($searchKeywords)
    {
        $this->searchKeywords = $searchKeywords;

        return $this;
    }

    /**
     * Get searchKeywords
     *
     * @return string
     */
    public function getSearchKeywords()
    {
        return $this->searchKeywords;
    }

    /**
     * Set cat
     *
     * @param Category $cat
     *
     * @return SearchTerm
     */
    public function setCat(Category $cat = null)
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Get cat
     *
     * @return Category
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Set dep
     *
     * @param Department $dep
     *
     * @return SearchTerm
     */
    public function setDep(Department $dep = null)
    {
        $this->dep = $dep;

        return $this;
    }

    /**
     * Get dep
     *
     * @return Department
     */
    public function getDep()
    {
        return $this->dep;
    }

    /**
     * Set usr
     *
     * @param User $usr
     *
     * @return SearchTerm
     */
    public function setUsr(User $usr = null)
    {
        $this->usr = $usr;

        return $this;
    }

    /**
     * Get usr
     *
     * @return User
     */
    public function getUsr()
    {
        return $this->usr;
    }
}
