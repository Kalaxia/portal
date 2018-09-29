<?php

namespace App\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Vote\Vote as VoteModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="vote__votes")
 */
class Vote extends VoteModel
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    protected $user;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Vote\Poll")
     */
    protected $poll;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Vote\Option")
     */
    protected $option;
}