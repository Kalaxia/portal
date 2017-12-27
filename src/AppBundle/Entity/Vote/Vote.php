<?php

namespace AppBundle\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Model\Vote\Vote as VoteModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="vote__votes")
 */
class Vote extends VoteModel
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    protected $user;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vote\Poll")
     */
    protected $poll;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vote\Option")
     */
    protected $option;
}