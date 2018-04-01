<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getLastUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->select()
            ->where('u.createdAt > :date')
            ->setParameter('date', new \DateTime('-7 days'))
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function searchUsers($criterias)
    {
        $queryBuilder = $this->createQueryBuilder('u')->select();
        
        if (isset($criterias['username'])) {
            $queryBuilder->andWhere('u.username LIKE :username')
                ->setParameter('username', "%{$criterias['username']}%");
        }
        if (isset($criterias['roles'])) {
            foreach($criterias['roles'] as $index => $role) {
                $queryBuilder->orWhere("u.roles LIKE :role{$index}")
                    ->setParameter("role{$index}", "%{$role}%");
            }
        }
        
        return $queryBuilder->getQuery()->getArrayResult();
    }
}