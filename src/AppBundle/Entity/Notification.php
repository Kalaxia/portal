<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\Notification as NotificationModel;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User") 
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