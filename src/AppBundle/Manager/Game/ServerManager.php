<?php

namespace AppBundle\Manager\Game;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Game\Server;

class ServerManager
{
    /** @var EntityManager **/
    protected $entityManager;
    
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @return array
     */
    public function getOpenedServers()
    {
        return $this->entityManager->getRepository(Server::class)->getOpenedServers();
    }
}