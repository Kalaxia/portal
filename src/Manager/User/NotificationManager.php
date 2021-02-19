<?php

namespace App\Manager\User;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User\Notification;
use App\Entity\User\User;

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    NotFoundHttpException
};

class NotificationManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(User $user, string $title, string $content): Notification
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

    public function read(int $id, User $user)
    {
        if (($notification = $this->entityManager->getRepository(Notification::class)->find($id)) === null) {
            throw new NotFoundHttpException('notifications.not_found');
        }
        if ($notification->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('notifications.access_denied');
        }
        $notification->setIsRead(true);
        $this->entityManager->flush($notification);
    }
}