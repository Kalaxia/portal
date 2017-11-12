<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Notification;
use AppBundle\Entity\User;

class NotificationManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @param User $user
     * @param string $title
     * @param string $content
     * @return Notification
     */
    public function create(User $user, $title, $content)
    {
        $notification =
            (new Notification())
            ->setUser($user)
            ->setTitle($title)
            ->setContent($content)
        ;
        $this->entityManager->persist($notification);
        $this->entityManager->flush($notification);
        return $notification;
    }
}