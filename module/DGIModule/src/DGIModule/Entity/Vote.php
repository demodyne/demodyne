<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */
 
namespace DGIModule\Entity;

use DGIModule\Entity\Proposal;
use DGIModule\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="dgi_votes", 
 *  uniqueConstraints={@ORM\UniqueConstraint(name="vote_unique", columns={"usr_id", "prop_id"})}, 
 *  indexes={
 *      @ORM\Index(name="id_user_fk_idx", columns={"usr_id"}), 
 *      @ORM\Index(name="id_proposition_fk_idx", columns={"prop_id"}),
 * })
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="vote_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $voteId;

    /**
     * @var integer
     *
     * @ORM\Column(name="vote_vote", type="integer", nullable=false)
     */
    private $voteVote=0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vote_priority", type="integer", nullable=false)
     */
    private $votePriority;

    /**
     * @var string
     *
     * @ORM\Column(name="vote_uuid", type="uuid",  length=36, nullable=true)
     */
    private $voteUUID;
    
 
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vote_created_date", type="utcdatetime", nullable=false)
     */
    private $voteCreatedDate;
    

    /**
     * @var Proposal
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\Proposal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prop_id", referencedColumnName="prop_id")
     * })
     */
    private $prop;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DGIModule\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * })
     */
    private $usr;


    public function nameVote() {
        $values = [-5=>"Opposed", -3=>"Unfavourable", 0=>"Neutral", 3=>"Favourable", 5=>"Highly Favourable"];
        return $values[$this->voteVote];
    }
    
    public function priorityVote() {
        $values = [10=>"Normal", 12=>"High", 14=>"Very High"];
        return $values[$this->votePriority];
    }
    
    /**
     * Get voteId
     *
     * @return integer 
     */
    public function getVoteId()
    {
        return $this->voteId;
    }

    /**
     * Set voteVote
     *
     * @param integer $voteVote
     * @return Vote
     */
    public function setVoteVote($voteVote)
    {
        $this->voteVote = $voteVote;

        return $this;
    }

    /**
     * Get voteVote
     *
     * @return integer 
     */
    public function getVoteVote()
    {
        return $this->voteVote;
    }

    /**
     * Set votePriority
     *
     * @param integer $votePriority
     * @return Vote
     */
    public function setVotePriority($votePriority)
    {
        $this->votePriority = $votePriority;

        return $this;
    }

    /**
     * Get votePriority
     *
     * @return integer 
     */
    public function getVotePriority()
    {
        return $this->votePriority;
    }


    
    /**
     * Get voteUUID
     *
     * @return string
     */
    public function getVoteUUID()
    {
        return $this->voteUUID;
    }
    
     /**
     * Set voteCreatedDate
     *
     * @param \DateTime $voteCreatedDate
     * @return Vote
     */
    public function setVoteCreatedDate($voteCreatedDate)
    {
        $this->voteCreatedDate = $voteCreatedDate;

        return $this;
    }

    /**
     * Get voteCreatedDate
     *
     * @return \DateTime 
     */
    public function getVoteCreatedDate()
    {
        return $this->voteCreatedDate;
    }
    
 
    /**
     * Set prop
     *
     * @param Proposal $prop
     * @return Vote
     */
    public function setProp(Proposal $prop = null)
    {
        $this->prop = $prop;

        return $this;
    }

    /**
     * Get prop
     *
     * @return Proposal
     */
    public function getProp()
    {
        return $this->prop;
    }

    /**
     * Set usr
     *
     * @param User $usr
     * @return Vote
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

    /**
     * @param string $voteUUID
     * @return Vote
     */
    public function setVoteUUID($voteUUID)
    {
        $this->voteUUID = $voteUUID;
        return $this;
    }

}
