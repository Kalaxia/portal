<?php

namespace AppBundle\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Model\Game\Server as ServerModel;

/**
 * @ORM\Table(name="game__servers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Game\ServerRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=15)
 * @ORM\DiscriminatorMap({
 *     "multiplayer" = "MultiplayerServer",
 *     "solo" = "SoloServer",
 *     "tutorial" = "TutorialServer"
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class Server extends ServerModel
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
     * @ORM\Column(type="string", length=80)
     */
    protected $slug;
    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $host;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $banner;
    /**
     * @ORM\Column(type="text")
     */
    protected $publicKey;
    /**
     * @ORM\Column(name="created_at", type="datetime") 
     */
    protected $createdAt;
    /**
     * @ORM\Column(name="started_at", type="datetime") 
     */
    protected $startedAt;
    /**
     * @ORM\Column(name="updated_at", type="datetime") 
     */
    protected $updatedAt;
    
    
    const TYPE_MULTIPLAYER = 'multiplayer';
    const TYPE_SOLO = 'solo';
    const TYPE_TUTORIAL = 'tutorial';
    
    /**
     * @return string
     */
    abstract public function getType();
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}

