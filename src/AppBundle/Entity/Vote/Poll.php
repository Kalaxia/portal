<?php

namespace AppBundle\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Model\Vote\Poll as PollModel;

/**
 * @ORM\Table(name="vote__polls")
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=15)
 * @ORM\DiscriminatorMap({
 *     "common" = "CommonPoll",
 *     "feature" = "FeaturePoll"
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class Poll extends PollModel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $endedAt;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isOver;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isApproved;
    
    const TYPE_COMMON = 'common';
    const TYPE_FEATURE = 'feature';
    
    /**
     * @return string
     */
    abstract public function getType();
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->isOver = false;
        $this->isApproved = false;
    }
}