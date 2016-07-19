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
 * Mesure
 *
 * @ORM\Table(name="dgi_mesures", indexes={@ORM\Index(name="mes_last_usr_fk_idx", columns={"last_usr_id"})})
 * @ORM\Entity
 */
class Measure
{
    /**
     * @var integer
     *
     * @ORM\Column(name="mes_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mesId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mes_start_date", type="datetime", nullable=true)
     */
    private $mesStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mes_end_date", type="datetime", nullable=true)
     */
    private $mesEndDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes_cost", type="integer", nullable=true)
     */
    private $mesCost = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection|Link[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\Link", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_mesures_links",
     *  joinColumns={
     *      @ORM\JoinColumn(name="mes_id", referencedColumnName="mes_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="link_id", referencedColumnName="link_id")
     *  }
     * )
     */
    private $links;
    
    /**
     * @var \Doctrine\Common\Collections\Collection|History[]
     *
     * @ORM\ManyToMany(targetEntity="DGIModule\Entity\History", cascade={"persist", "merge","remove"})
     * @ORM\JoinTable(
     *  name="dgi_mesures_history",
     *  joinColumns={
     *      @ORM\JoinColumn(name="mes_id", referencedColumnName="mes_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="his_id", referencedColumnName="his_id")
     *  }
     * )
     */
    private $history;

    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->history = new ArrayCollection();
    }
    
    public function getLinks()
    {
        $urls = [];
        foreach ($this->links as $link) {
            $urls[] = $link->getLinkUrl();
        }
        
        return $urls;
    }
    
    /**
     * @param Link $link
     */
    public function addLink($url)
    {
        
        $this->links->add(new Link($url));
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
    
    public function getHistory()
    {
        return $this->history;
    }
    
    /**
     * @param Link $link
     */
    public function addHistory(History $history)
    {
        if ($this->history->contains($history)) {
            return;
        }
        $this->history->add($history);
    }
    
    /**
     * @param Link $link
     */
    public function removeHistory(History $history)
    {
        if (!$this->history->contains($history)) {
            return;
        }
    
        $this->history->removeElement($history);
    
    }
    
    public function clearHistory() {
        $this->history->clear();
    }

    /**
     * Get mesId
     *
     * @return integer
     */
    public function getMesId()
    {
        return $this->mesId;
    }

        /**
     * Set mesStartDate
     *
     * @param \DateTime $mesStartDate
     *
     * @return Mesure
     */
    public function setMesStartDate($mesStartDate)
    {
        $this->mesStartDate = $mesStartDate;

        return $this;
    }

    /**
     * Get mesStartDate
     *
     * @return \DateTime
     */
    public function getMesStartDate()
    {
        return $this->mesStartDate;
    }

    /**
     * Set mesEndDate
     *
     * @param \DateTime $mesEndDate
     *
     * @return Mesure
     */
    public function setMesEndDate($mesEndDate)
    {
        $this->mesEndDate = $mesEndDate;

        return $this;
    }

    /**
     * Get mesEndDate
     *
     * @return \DateTime
     */
    public function getMesEndDate()
    {
        return $this->mesEndDate;
    }

    /**
     * Set mesCost
     *
     * @param integer $mesCost
     *
     * @return Mesure
     */
    public function setMesCost($mesCost)
    {
        $this->mesCost = $mesCost;

        return $this;
    }

    /**
     * Get mesCost
     *
     * @return integer
     */
    public function getMesCost()
    {
        return $this->mesCost;
    }

   
}
