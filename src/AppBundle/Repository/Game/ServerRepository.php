<?php

namespace AppBundle\Repository\Game;

use Doctrine\ORM\EntityRepository;

class ServerRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getOpenedServers()
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('s')
            ->from($this->getEntityName(), 's')
            ->where('s.startedAt <= CURRENT_TIMESTAMP()')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return array
     */
    public function getNextServers()
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('s')
            ->from($this->getEntityName(), 's')
            ->where('s.startedAt > CURRENT_TIMESTAMP()')
            ->getQuery()
            ->getResult()
        ;
    }
}