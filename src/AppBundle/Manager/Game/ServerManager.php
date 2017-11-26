<?php

namespace AppBundle\Manager\Game;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Game\{
    MultiplayerServer,
    Server,
    SoloServer,
    TutorialServer
};

class ServerManager
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
    
    /**
     * @return array
     */
    public function getOpenedServers()
    {
        return $this->entityManager->getRepository(Server::class)->getOpenedServers();
    }
    
    /**
     * @return array
     */
    public function getNextServers()
    {
        return $this->entityManager->getRepository(Server::class)->getNextServers();
    }
    
    /**
     * @param string $name
     * @param string $description
     * @param string $banner
     * @param string $startedAt
     * @param string $publicKey
     * @param string $type
     */
    public function create($name, $description, $banner, $startedAt, $publicKey, $type)
    {
        $serverClass = [
            Server::TYPE_MULTIPLAYER => MultiplayerServer::class,
            Server::TYPE_SOLO => SoloServer::class,
            Server::TYPE_TUTORIAL => TutorialServer::class
        ][$type];
        $server =
            (new $serverClass())
            ->setName($name)
            ->setDescription($description)
            ->setBanner($banner)
            ->setStartedAt(new \DateTime($startedAt))
            ->setPublicKey($publicKey)
        ;
        $this->entityManager->persist($server);
        $this->entityManager->flush($server);
    }
}