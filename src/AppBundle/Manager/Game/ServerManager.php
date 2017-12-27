<?php

namespace AppBundle\Manager\Game;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\User;
use AppBundle\Entity\Game\{
    MultiplayerServer,
    Server,
    SoloServer,
    TutorialServer
};
use AppBundle\Gateway\ServerGateway;
use AppBundle\Security\RsaEncryptionManager;

use AppBundle\Utils\Slugger;

class ServerManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    /** @var ServerGateway **/
    protected $serverGateway;
    /** @var RsaEncryptionManager **/
    protected $rsaEncryptionManager;
    /** @var Slugger **/
    protected $slugger;
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param ServerGateway $serverGateway
     * @param RsaEncryptionManager $rsaEncryptionManager
     * @param Slugger $slugger
     */
    public function __construct(EntityManagerInterface $entityManager, ServerGateway $serverGateway, RsaEncryptionManager $rsaEncryptionManager, Slugger $slugger)
    {
        $this->entityManager = $entityManager;
        $this->serverGateway = $serverGateway;
        $this->rsaEncryptionManager = $rsaEncryptionManager;
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
    public function getAvailableServers(User $user)
    {
        $servers = $this->getOpenedServers();
        foreach ($servers as $key => $server) {
            if ($user->hasServer($server)) {
                unset($servers[$key]);
            }
        }
        return array_values($servers);
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
        $signature = md5($name . uniqid(true));
        $server =
            (new $serverClass())
            ->setName($name)
            ->setSlug($this->slugger->slugify($name))
            ->setHost($host)
            ->setDescription($description)
            ->setBanner($banner)
            ->setSignature($signature)
            ->setStartedAt(new \DateTime($startedAt))
            ->setPublicKey($publicKey)
        ;
        $response = $this->serverGateway->bindServer($host, $this->rsaEncryptionManager->encrypt($server, json_encode([
            'name' => $name,
            'type' => $type,
            'signature' => $signature,
            'map_size' => 100,
        ])));
        if ($response->getStatusCode() !== 201) {
            throw new \ErrorException($response->getBody()->getContent());
        }
        $this->entityManager->persist($server);
        $this->entityManager->flush($server);
    }
    
    /**
     * @param Server $server
     * @param User $user
     * @return string
     */
    public function joinServer(Server $server, User $user)
    {
        $response = $this->serverGateway->connectPlayer(
            $server->getHost(),
            $this->rsaEncryptionManager->encrypt($server, json_encode([
                'username' => $user->getUsername(),
                'signature' => $server->getSignature()
            ]))
        );
        
        $jwt = $this->rsaEncryptionManager->decrypt($response->getBody()->getContents());
        
        if (!$user->hasServer($server)) {
            $user->addServer($server);
            $this->entityManager->flush($user);
        }
        return $jwt;
    }
}