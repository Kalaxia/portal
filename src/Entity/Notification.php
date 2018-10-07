<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Model\Notification as NotificationModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="notifications")
 * @ORM\HasLifecycleCallbacks
 */
class Notification extends NotificationModel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notifications") 
     */
    protected $user;
    /**
     * @ORM\Column(type="string", length=60) 
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=255) 
     */
    protected $content;
    /**
     * @ORM\Column(name="is_read", type="boolean") 
     */
    protected $isRead;
    /**
     * @ORM\Column(name="created_at", type="datetime") 
     */
    protected $createdAt;
    /**
     * @ORM\Column(name="updated_at", type="datetime") 
     */
    protected $updatedAt;
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
        $this->isRead = false;
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}