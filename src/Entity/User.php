<?php
namespace App\Entity;

use FOS\UserBundle\Model\User as UserModel;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

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

    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
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