<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as UserModel;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\Game\Server;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends UserModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Game\Server")
     * @ORM\JoinTable(
     *  name="game__players",
     *  inverseJoinColumns={@ORM\JoinColumn(referencedColumnName="id")}
     * )
     */
    protected $servers;

    public function __construct()
    {
        parent::__construct();
        $this->servers = new ArrayCollection();
    }
    
    /**
     * @param Server $server
     * @return $this
     */
    public function addServer(Server $server)
    {
        $this->servers->add($server);
        
        return $this;
    }
    
    /**
     * @param Server $server
     * @return $this
     */
    public function removeServer(Server $server)
    {
        $this->servers->removeElement($server);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getServers()
    {
        return $this->servers;
    }
}