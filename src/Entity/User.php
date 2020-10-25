<?php
namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user__users")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $activationToken;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isEnabled;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

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
        $this->notifications = new ArrayCollection();
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function setId(int $id): self
    {
        $this->id = $id;
        
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function eraseCredentials()
    {

    }

    public function enable(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setActivationToken(?string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function getRoles(): array
    {
        return array_merge(['ROLE_USER'], $this->roles);
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function addNotification(Notification $notification): self
    {
        $this->notifications->add($notification);
        
        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        $this->notifications->removeElement($notification);
        
        return $this;
    }

    public function getNotifications(): ArrayCollection
    {
        return $this->notifications;
    }
    
    public function getUnreadNotifications(): ArrayCollection
    {
        $data = new ArrayCollection();
        foreach ($this->notifications as $notification) {
            if (!$notification->getIsRead()) {
                $data->add($notification);
            }
        }
        return $data;
    }
    
    public function jsonSerialize(): array
    {
        return [
            'username' => $this->username,
            'roles' => $this->roles,
        ];
    }
}