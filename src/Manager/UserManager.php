<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

class UserManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getLastUsers(): array
    {
        return $this->em->getRepository(User::class)->getLastUsers();
    }
    
    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }
    
    /**
     * @param array $criterias
     * @return array
     */
    public function search(array $criterias): array
    {
        return $this->em->getRepository(User::class)->searchUsers($criterias);
    }
}