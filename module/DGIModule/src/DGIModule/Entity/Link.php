<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 *
 * @ORM\Table(name="dgi_links")
 * @ORM\Entity
 */
class Link
{
    /**
     * @var integer
     *
     * @ORM\Column(name="link_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $linkId;

    /**
     * @var string
     *
     * @ORM\Column(name="link_url", type="string", length=200, nullable=false)
     */
    private $linkUrl = '';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="link_added", type="integer", nullable=true)
     */
    private $linkAdded = '1';
    

    public function __construct($url = '', $added = 1) {
        $this->linkUrl = $url;
        $this->linkAdded = $added;
    }
    
    /**
     * Get linkId
     *
     * @return integer
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * Set linkUrl
     *
     * @param string $linkUrl
     *
     * @return Link
     */
    public function setLinkUrl($linkUrl)
    {
        $this->linkUrl = $linkUrl;

        return $this;
    }

    /**
     * Get linkUrl
     *
     * @return string
     */
    public function getLinkUrl()
    {
        return $this->linkUrl;
    }
    
    /**
     * Set linkAdded
     *
     * @param integer $linkAdded
     *
     * @return Link
     */
    public function setLinkAdded($linkAdded)
    {
        $this->linkAdded = $linkAdded;
    
        return $this;
    }
    
    /**
     * Get linkAdded
     *
     * @return integer
     */
    public function getLinkAdded()
    {
        return $this->linkAdded;
    }
    
}
