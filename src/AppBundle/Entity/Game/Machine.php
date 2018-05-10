<?php

namespace AppBundle\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Model\Game\Machine as MachineModel;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Game\MachineRepository")
 * @ORM\Table(name="game__machines")
 */
class Machine extends MachineModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=80, unique=true)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=80, unique=true)
     */
    protected $slug;
    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $host;
    /**
     * @ORM\Column(type="text")
     */
    protected $publicKey;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isLocal;
}