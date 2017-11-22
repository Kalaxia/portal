<?php

namespace AppBundle\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="game__tutorial_servers")
 */
class TutorialServer extends Server
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    protected $owner;
    
    /**
     * @param User $owner
     * @return $this
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
        
        return $this;
    }
    
    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }
    
    public function getType()
    {
        return self::TYPE_TUTORIAL;
    }
}
