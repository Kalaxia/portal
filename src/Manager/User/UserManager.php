<?php

namespace App\Manager\User;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User\User;

class UserManager
{
    /** @var EntityManagerInterface **/
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getLastUsers(): array
    {
        return $this->em->getRepository(User::class)->getLastUsers();
    }

    public function getAll(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    public function search(array $criterias): array
    {
        return $this->em->getRepository(User::class)->searchUsers($criterias);
    }
}