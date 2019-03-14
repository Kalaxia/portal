<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Game\FactionColors as FactionColorsModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="game__factions_colors")
 */
class FactionColors extends FactionColorsModel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $black;
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $grey;
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $white;
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $main;
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $mainLight;
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $mainLighter;
}
