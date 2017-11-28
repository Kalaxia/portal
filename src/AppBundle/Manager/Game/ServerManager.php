<?php

namespace AppBundle\Manager\Game;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Game\{
    MultiplayerServer,
    Server,
    SoloServer,
    TutorialServer
};

use AppBundle\Utils\Slugger;

class ServerManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    /** @var Slugger **/
    protected $slugger;
    
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, Slugger $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }
    
    /**
     * @param int $id
     * @return Server
     */
    public function get($id)
    {
        return $this->entityManager->getRepository(Server::class)->find($id);
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
     * @param string $host
     * @param string $description
     * @param string $banner
     * @param string $startedAt
     * @param string $publicKey
     * @param string $type
     */
    public function create($name, $host, $description, $banner, $startedAt, $publicKey, $type)
    {
        $serverClass = [
            Server::TYPE_MULTIPLAYER => MultiplayerServer::class,
            Server::TYPE_SOLO => SoloServer::class,
            Server::TYPE_TUTORIAL => TutorialServer::class
        ][$type];
        $server =
            (new $serverClass())
            ->setName($name)
            ->setSlug($this->slugger->slugify($name))
            ->setHost($host)
            ->setDescription($description)
            ->setBanner($banner)
            ->setStartedAt(new \DateTime($startedAt))
            ->setPublicKey($publicKey)
        ;
        $this->entityManager->persist($server);
        $this->entityManager->flush($server);
    }
}