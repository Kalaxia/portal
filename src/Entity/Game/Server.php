<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\User;

use App\Model\Game\Server as ServerModel;

/**
 * @ORM\Table(name="game__servers")
 * @ORM\Entity(repositoryClass="App\Repository\Game\ServerRepository")
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
    const DEFAULT_BANNER = 'illu2_v5.png';
    
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
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $banner;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Game\Faction")
     * @ORM\JoinTable(name="game__server_factions")
     */
    protected $factions;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="servers")
     * @ORM\JoinTable(
     *  name="game__players",
     *  inverseJoinColumns={@ORM\JoinColumn(referencedColumnName="id")}
     * )
     */
    protected $players;
    /**
     * @ORM\Column(type="integer")
     */
    protected $gameId;
    /**
     * @ORM\Column(type="string", length=85) 
     */
    protected $signature;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game\Machine")
     */
    protected $machine;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $subDomain;
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
    
    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->factions = new ArrayCollection();
    }
    
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
    
    /**
     * @param Faction $faction
     * @return $this
     */
    public function addFaction(Faction $faction): self
    {
        $this->factions->add($faction);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getFactions()
    {
        return $this->factions;
    }

    public function addPlayer(User $player): self
    {
        $this->players->add($player);

        return $this;
    }

    public function removePlayer(User $player): self
    {
        $this->players->removeElement($player);

        return $this;
    }

    public function getPlayers()
    {
        return $this->players;
    }
}

