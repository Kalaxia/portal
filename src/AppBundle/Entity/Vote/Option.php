<?php

namespace AppBundle\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Model\Vote\Option as OptionModel;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vote\Poll")
     */
    protected $poll;
    /**
     * @ORM\Column(type="string", length=10) 
     */
    protected $color;
    /**
     * @ORM\Column(type="string", length=100) 
     */
    protected $value;
}