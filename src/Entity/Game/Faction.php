<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Game\Faction as FactionModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="game__factions")
 */
class Faction extends FactionModel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $name;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\OneToOne(targetEntity="FactionColors")
     * @ORM\JoinColumn(name="color", referencedColumnName="id")
     */
    protected $color;
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $banner;
}
