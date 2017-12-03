<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Notification;
use AppBundle\Entity\User;

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    NotFoundHttpException
};

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
    
    /**
     * @param int $id
     * @param User $user
     * @throws NotFoundHttpException
     * @throws AccessDeniedHttpException
     */
    public function read($id, User $user)
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