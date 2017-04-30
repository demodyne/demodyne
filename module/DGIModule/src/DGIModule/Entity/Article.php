<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DGIModule/Entity/DgiArticles
 *
 * @ORM\Table(name="dgi_articles", indexes={@ORM\Index(name="fk_dgi_articles_1", columns={"usr_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $articleId;
    /**
     * @var string
     *
     * @ORM\Column(name="article_title", type="text", length=65535, nullable=false)
     */
    private $articleTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="article_description", type="text", length=65535, nullable=false)
     */
    private $articleDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="article_created_date", type="datetime", nullable=true)
     */
    private $articleCreatedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="article_published_date", type="datetime", nullable=true)
     */
    private $articlePublishedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="article_category", type="integer", nullable=true)
     */
    private $articleCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="article_image", type="string", length=500, nullable=true)
     */
    private $articleImage;

    /**
     * @var integer
     *
     * @ORM\Column(name="article_featured", type="integer", nullable=true)
     */
    private $articleFeatured = 0;

    /**
     * @return int
     */
    public function getArticleFeatured()
    {
        return $this->articleFeatured;
    }

    /**
     * @param int $articleFeatured
     * @return Article
     */
    public function setArticleFeatured($articleFeatured)
    {
        $this->articleFeatured = $articleFeatured;
        return $this;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="article_views", type="integer", nullable=true)
     */
    private $articleViews = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="article_slug", type="string", length=1024, nullable=true)
     */
    private $articleSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="article_uuid", type="uuid", length=36, nullable=true)
     */
    private $articleUUID;

    /**
     * @return string
     */
    public function getArticleUUID()
    {
        return $this->articleUUID;
    }

    /**
     * @param string $articleUUID
     * @return Article
     */
    public function setArticleUUID($articleUUID)
    {
        $this->articleUUID = $articleUUID;
        return $this;
    }

    /**
     * @return string
     */
    public function getArticleSlug()
    {
        return $this->articleSlug;
    }

    /**
     * @param string $articleSlug
     * @return Article
     */
    public function setArticleSlug($articleSlug)
    {
        $this->articleSlug = $articleSlug;
        return $this;
    }

    /**
     * @var \DGIModule\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;

    /**
     * @var \DGIModule\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;

    /** @ORM\OneToMany(targetEntity="DGIModule\Entity\Comment", mappedBy="article") */
    private $comments;

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @return \DGIModule\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param \DGIModule\Entity\Country $country
     * @return Article
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->articleId;
    }


    /**
     * @return string
     */
    public function getArticleTitle()
    {
        return $this->articleTitle;
    }

    /**
     * @param string $articleTitle
     * @return Article
     */
    public function setArticleTitle($articleTitle)
    {
        $this->articleTitle = $articleTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getArticleDescription()
    {
        return $this->articleDescription;
    }

    /**
     * @param string $articleDescription
     * @return Article
     */
    public function setArticleDescription($articleDescription)
    {
        $this->articleDescription = $articleDescription;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getArticleCreatedDate()
    {
        return $this->articleCreatedDate;
    }

    /**
     * @param \DateTime $articleCreatedDate
     * @return Article
     */
    public function setArticleCreatedDate($articleCreatedDate)
    {
        $this->articleCreatedDate = $articleCreatedDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getArticlePublishedDate()
    {
        return $this->articlePublishedDate;
    }

    /**
     * @param \DateTime $articlePublishedDate
     * @return Article
     */
    public function setArticlePublishedDate($articlePublishedDate)
    {
        $this->articlePublishedDate = $articlePublishedDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleCategory()
    {
        return $this->articleCategory;
    }

    /**
     * @param int $articleCategory
     * @return Article
     */
    public function setArticleCategory($articleCategory)
    {
        $this->articleCategory = $articleCategory;
        return $this;
    }

    /**
     * @return string
     */
    public function getArticleImage()
    {
        return $this->articleImage;
    }

    /**
     * @param string $articleImage
     * @return Article
     */
    public function setArticleImage($articleImage)
    {
        $this->articleImage = $articleImage;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleViews()
    {
        return $this->articleViews;
    }

    /**
     * @param int $articleViews
     * @return Article
     */
    public function setArticleViews($articleViews)
    {
        $this->articleViews = $articleViews;
        return $this;
    }


    /**
     * @return \DGIModule\Entity\User
     */
    public function getUsr()
    {
        return $this->usr;
    }

    /**
     * @param \DGIModule\Entity\User $usr
     * @return Article
     */
    public function setUsr(User $usr)
    {
        $this->usr = $usr;
        return $this;
    }


}
