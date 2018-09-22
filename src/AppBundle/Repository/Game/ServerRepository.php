<?php

namespace AppBundle\Repository\Game;

use Doctrine\ORM\EntityRepository;

class ServerRepository extends EntityRepository
{
    public function getOpenedServers(): array
    {
        return $this
            ->createQueryBuilder('s')
            ->select()
            ->where('s.startedAt <= CURRENT_TIMESTAMP()')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function getNextServers(): array
    {
        return $this
            ->createQueryBuilder('s')
            ->select()
            ->where('s.startedAt > CURRENT_TIMESTAMP()')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function countServersPlayers(): array
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s.id', 'COUNT(p.id) as player_count')
            ->leftJoin('s.players', 'p')
            ->groupBy('s.id')
            ->getQuery()
            ->getScalarResult()
        ;
    }
}