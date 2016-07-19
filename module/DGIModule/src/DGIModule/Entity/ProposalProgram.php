<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProposalPrograms
 *
 * @ORM\Table(name="dgi_proposals_programs", indexes={@ORM\Index(name="scn_prop_id_fk1", columns={"prop_id"}), @ORM\Index(name="prop_scn_id_fk1_idx", columns={"prog_id"})})
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\ProposalProgramRepository")
 */
class ProposalProgram
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort_position", type="integer", nullable=true)
     */
    private $sortPosition = 1000;

    /**
     * @var \DGIModule\Entity\Program
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Program")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prog_id", referencedColumnName="prog_id")
     * })
     */
    private $prog;

    /**
     * @var \DGIModule\Entity\Proposal
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Proposal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sortPosition
     *
     * @param integer $sortPosition
     *
     * @return ProposalPrograms
     */
    public function setSortPosition($sortPosition)
    {
        $this->sortPosition = $sortPosition;

        return $this;
    }

    /**
     * Get sortPosition
     *
     * @return integer
     */
    public function getSortPosition()
    {
        return $this->sortPosition;
    }

    /**
     * Set prog
     *
     * @param \DGIModule\Entity\Program $prog
     *
     * @return ProposalPrograms
     */
    public function setProg(\DGIModule\Entity\Program $prog = null)
    {
        $this->prog = $prog;

        return $this;
    }

    /**
     * Get prog
     *
     * @return \DGIModule\Entity\Program
     */
    public function getProg()
    {
        return $this->prog;
    }

    /**
     * Set prop
     *
     * @param \DGIModule\Entity\Proposal $prop
     *
     * @return ProposalPrograms
     */
    public function setProp(\DGIModule\Entity\Proposal $prop = null)
    {
        $this->prop = $prop;

        return $this;
    }

    /**
     * Get prop
     *
     * @return \DGIModule\Entity\Proposal
     */
    public function getProp()
    {
        return $this->prop;
    }
}
