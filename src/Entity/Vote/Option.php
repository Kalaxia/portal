<?php

namespace App\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Vote\Option as OptionModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="vote__options")
 */
class Option extends OptionModel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vote\Poll")
     */
    protected $poll;
    /**
     * @ORM\Column(type="string", length=100) 
     */
    protected $value;
    
    const VALUE_YES = 'project.votes.yes';
    const VALUE_NO = 'project.votes.no';
}