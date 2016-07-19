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
 * Newsletter
 *
 * @ORM\Table(name="dgi_newsletters", indexes={@ORM\Index(name="fk_admin_nl_idx", columns={"admin_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\NewsletterRepository")
 */
class Newsletter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="nl_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nlId;

    /**
     * @var string
     *
     * @ORM\Column(name="nl_name", type="string", length=100, nullable=false)
     */
    private $nlName;

    /**
     * @var string
     *
     * @ORM\Column(name="nl_description", type="string", length=1000, nullable=true)
     */
    private $nlDescription = '';

    /**
     * @var string
     *
     * @ORM\Column(name="nl_header_image", type="string", length=200, nullable=true)
     */
    private $nlHeaderImage = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="nl_send_to", type="integer", nullable=true)
     */
    private $nlSendTo = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="nl_subject", type="string", length=200, nullable=true)
     */
    private $nlSubject = '';

    /**
     * @var string
     *
     * @ORM\Column(name="nl_contact", type="string", length=200, nullable=true)
     */
    private $nlContact = '';

    /**
     * @var string
     *
     * @ORM\Column(name="nl_message", type="text", length=65535, nullable=true)
     */
    private $nlMessage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nl_created_date", type="datetime", nullable=true)
     */
    private $nlCreatedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nl_sent_date", type="datetime", nullable=true)
     */
    private $nlSentDate;

    /**
     * @var string
     *
     * @ORM\Column(name="nl_uuid", type="string", length=36, nullable=true)
     */
    private $nlUUID;

    /**
     * @var integer
     *
     * @ORM\Column(name="nl_reply", type="integer", nullable=true)
     */
    private $nlReply = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="nl_url", type="string", length=200, nullable=true)
     */
    private $nlUrl = '';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nl_is_sent", type="integer", nullable=true)
     */
    private $nlIsSent = '0';

    /**
     * @var \DGIModule\Entity\Administration
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Administration", cascade={"persist", "merge","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_id", referencedColumnName="admin_id")
     * })
     */
    private $admin;
    
    /**
     * @var \Doctrine\Common\Collections\Collection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Category", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_newsletters_categories",
     *  joinColumns={
     *      @ORM\JoinColumn(name="nl_id", referencedColumnName="nl_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     *  }
     * )
     */
    private $categories;
    
    /**
     * @var \DGIModule\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="city_id")
     * })
     */
    private $city;
    
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
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     * })
     */
    private $region;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Get nlId
     *
     * @return integer
     */
    public function getNlId()
    {
        return $this->nlId;
    }

    /**
     * Set nlName
     *
     * @param string $nlName
     *
     * @return Newsletter
     */
    public function setNlName($nlName)
    {
        $this->nlName = $nlName;

        return $this;
    }

    /**
     * Get nlName
     *
     * @return string
     */
    public function getNlName()
    {
        return $this->nlName;
    }

    /**
     * Set nlDescription
     *
     * @param string $nlDescription
     *
     * @return Newsletter
     */
    public function setNlDescription($nlDescription)
    {
        $this->nlDescription = $nlDescription;

        return $this;
    }

    /**
     * Get nlDescription
     *
     * @return string
     */
    public function getNlDescription()
    {
        return $this->nlDescription;
    }

    /**
     * Set nlHeaderImage
     *
     * @param string $nlHeaderImage
     *
     * @return Newsletter
     */
    public function setNlHeaderImage($nlHeaderImage)
    {
        $this->nlHeaderImage = $nlHeaderImage;

        return $this;
    }

    /**
     * Get nlHeaderImage
     *
     * @return string
     */
    public function getNlHeaderImage()
    {
        return $this->nlHeaderImage;
    }

    /**
     * Set nlSendTo
     *
     * @param integer $nlSendTo
     *
     * @return Newsletter
     */
    public function setNlSendTo($nlSendTo)
    {
        $this->nlSendTo = $nlSendTo;

        return $this;
    }

    /**
     * Get nlSendTo
     *
     * @return integer
     */
    public function getNlSendTo()
    {
        return $this->nlSendTo;
    }

    /**
     * Set nlSubject
     *
     * @param string $nlSubject
     *
     * @return Newsletter
     */
    public function setNlSubject($nlSubject)
    {
        $this->nlSubject = $nlSubject;

        return $this;
    }

    /**
     * Get nlSubject
     *
     * @return string
     */
    public function getNlSubject()
    {
        return $this->nlSubject;
    }

    /**
     * Set nlContact
     *
     * @param string $nlContact
     *
     * @return Newsletter
     */
    public function setNlContact($nlContact)
    {
        $this->nlContact = $nlContact;

        return $this;
    }

    /**
     * Get nlContact
     *
     * @return string
     */
    public function getNlContact()
    {
        return $this->nlContact;
    }

    /**
     * Set nlMessage
     *
     * @param string $nlMessage
     *
     * @return Newsletter
     */
    public function setNlMessage($nlMessage)
    {
        $this->nlMessage = $nlMessage;

        return $this;
    }

    /**
     * Get nlMessage
     *
     * @return string
     */
    public function getNlMessage()
    {
        return $this->nlMessage;
    }

    /**
     * Set nlCreatedDate
     *
     * @param \DateTime $nlCreatedDate
     *
     * @return Newsletter
     */
    public function setNlCreatedDate($nlCreatedDate)
    {
        $this->nlCreatedDate = $nlCreatedDate;

        return $this;
    }

    /**
     * Get nlCreatedDate
     *
     * @return \DateTime
     */
    public function getNlCreatedDate()
    {
        return $this->nlCreatedDate;
    }

    /**
     * Set nlSentDate
     *
     * @param \DateTime $nlSentDate
     *
     * @return Newsletter
     */
    public function setNlSentDate($nlSentDate)
    {
        $this->nlSentDate = $nlSentDate;

        return $this;
    }

    /**
     * Get nlSentDate
     *
     * @return \DateTime
     */
    public function getNlSentDate()
    {
        return $this->nlSentDate;
    }

    /**
     * Get nlUUID
     *
     * @return string
     */
    public function getNlUUID()
    {
        return $this->nlUUID;
    }

    /**
     * Set nlReply
     *
     * @param integer $nlReply
     *
     * @return Newsletter
     */
    public function setNlReply($nlReply)
    {
        $this->nlReply = $nlReply;

        return $this;
    }

    /**
     * Get nlReply
     *
     * @return integer
     */
    public function getNlReply()
    {
        return $this->nlReply;
    }

    /**
     * Set nlUrl
     *
     * @param string $nlUrl
     *
     * @return Newsletter
     */
    public function setNlUrl($nlUrl)
    {
        $this->nlUrl = $nlUrl;

        return $this;
    }

    /**
     * Get nlUrl
     *
     * @return string
     */
    public function getNlUrl()
    {
        return $this->nlUrl;
    }

    /**
     * Set nlIsSent
     *
     * @param integer $nlIsSent
     *
     * @return Newsletter
     */
    public function setNlIsSent($nlIsSent)
    {
        $this->nlIsSent = $nlIsSent;
    
        return $this;
    }
    
    /**
     * Get nlIsSent
     *
     * @return integer
     */
    public function getNlIsSent()
    {
        return $this->nlIsSent;
    }
    
    /**
     * Set admin
     *
     * @param \DGIModule\Entity\Administration $admin
     *
     * @return Newsletter
     */
    public function setAdmin(\DGIModule\Entity\Administration $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \DGIModule\Entity\Administration
     */
    public function getAdmin()
    {
        return $this->admin;
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
     * Set city
     *
     * @param \DGIModule\Entity\City $city
     *
     * @return Newsletter
     */
    public function setCity(\DGIModule\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }
    
    /**
     * Get city
     *
     * @return \DGIModule\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * Set country
     *
     * @param \DGIModule\Entity\Country $country
     *
     * @return Newsletter
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
     * Set region
     *
     * @param \DGIModule\Entity\Region $region
     *
     * @return Newsletter
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
