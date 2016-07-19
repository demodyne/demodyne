<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Counters
 *
 * @ORM\Table(name="dgi_counters")
 * @ORM\Entity
 */
class Counters
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cnt_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cntId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cnt_updated_date", type="datetime", nullable=true)
     */
    private $cntUpdatedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="cnt_total", type="integer", nullable=true)
     */
    private $cntTotal = 80;

    /**
     * @var integer
     *
     * @ORM\Column(name="cnt_prop", type="integer", nullable=true)
     */
    private $cntProp = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="cnt_vote", type="integer", nullable=true)
     */
    private $cntVote = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="cnt_com", type="integer", nullable=true)
     */
    private $cntCom = 0;



    /**
     * Get cntId
     *
     * @return integer
     */
    public function getCntId()
    {
        return $this->cntId;
    }

    /**
     * Set cntUpdatedDate
     *
     * @param \DateTime $cntUpdatedDate
     *
     * @return Counters
     */
    public function setCntUpdatedDate($cntUpdatedDate)
    {
        $this->cntUpdatedDate = $cntUpdatedDate;

        return $this;
    }

    /**
     * Get cntUpdatedDate
     *
     * @return \DateTime
     */
    public function getCntUpdatedDate()
    {
        return $this->cntUpdatedDate;
    }

    /**
     * Set cntTotal
     *
     * @param integer $cntTotal
     *
     * @return Counters
     */
    public function setCntTotal($cntTotal)
    {
        $this->cntTotal = $cntTotal;

        return $this;
    }

    /**
     * Get cntTotal
     *
     * @return integer
     */
    public function getCntTotal()
    {
        return $this->cntTotal;
    }

    /**
     * Set cntProp
     *
     * @param integer $cntProp
     *
     * @return Counters
     */
    public function setCntProp($cntProp)
    {
        $this->cntProp = $cntProp;

        return $this;
    }

    /**
     * Get cntProp
     *
     * @return integer
     */
    public function getCntProp()
    {
        return $this->cntProp;
    }

    /**
     * Set cntVote
     *
     * @param integer $cntVote
     *
     * @return Counters
     */
    public function setCntVote($cntVote)
    {
        $this->cntVote = $cntVote;

        return $this;
    }

    /**
     * Get cntVote
     *
     * @return integer
     */
    public function getCntVote()
    {
        return $this->cntVote;
    }

    /**
     * Set cntCom
     *
     * @param integer $cntCom
     *
     * @return Counters
     */
    public function setCntCom($cntCom)
    {
        $this->cntCom = $cntCom;

        return $this;
    }

    /**
     * Get cntCom
     *
     * @return integer
     */
    public function getCntCom()
    {
        return $this->cntCom;
    }
}
