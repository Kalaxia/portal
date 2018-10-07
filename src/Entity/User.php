<?php
namespace App\Entity;

use FOS\UserBundle\Model\User as UserModel;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Game\Server;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends UserModel implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user")
     */
    protected $notifications;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Game\Server", mappedBy="players")
     */
    protected $servers;

    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
        $this->servers = new ArrayCollection();
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * @return bool
     */
    public function hasServer(Server $server)
    {
        return $this->servers->contains($server);
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
    
    /**
     * @param \App\Entity\Notification $notification
     * @return $this
     */
    public function addNotification(Notification $notification)
    {
        $this->notifications->add($notification);
        
        return $this;
    }
    
    /**
     * @param \App\Entity\Notification $notification
     * @return $this
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
    
    public function getUnreadNotifications()
    {
        $data = new ArrayCollection();
        foreach ($this->notifications as $notification) {
            if (!$notification->getIsRead()) {
                $data->add($notification);
            }
        }
        return $data;
    }
    
    public function jsonSerialize()
    {
        return [
            'username' => $this->username,
            'roles' => $this->roles,
        ];
    }
}