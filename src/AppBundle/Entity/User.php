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
class User extends UserModel implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notification", mappedBy="user")
     */
    protected $notifications;
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
        $this->notifications = new ArrayCollection();
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
     * @param \AppBundle\Entity\Notification $notification
     * @return $this
     */
    public function addNotification(Notification $notification)
    {
        $this->notifications->add($notification);
        
        return $this;
    }
    
    /**
     * @param \AppBundle\Entity\Notification $notification
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
            'username' => $this->username
        ];
    }
}