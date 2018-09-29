<?php

namespace App\Manager\Game;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Entity\Game\{
    MultiplayerServer,
    Server,
    SoloServer,
    TutorialServer
};
use App\Gateway\ServerGateway;
use App\Security\RsaEncryptionManager;

use App\Utils\Slugger;

use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServerManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    /** @var FactionManager **/
    protected $factionManager;
    /** @var MachineManager **/
    protected $machineManager;
    /** @var ServerGateway **/
    protected $serverGateway;
    /** @var RsaEncryptionManager **/
    protected $rsaEncryptionManager;
    /** @var Slugger **/
    protected $slugger;
    
    public function __construct(EntityManagerInterface $entityManager, FactionManager $factionManager, MachineManager $machineManager, ServerGateway $serverGateway, RsaEncryptionManager $rsaEncryptionManager, Slugger $slugger)
    {
        $this->entityManager = $entityManager;
        $this->factionManager = $factionManager;
        $this->machineManager = $machineManager;
        $this->serverGateway = $serverGateway;
        $this->rsaEncryptionManager = $rsaEncryptionManager;
        $this->slugger = $slugger;
    }
    
    public function get(int $id): Server
    {
        return $this->entityManager->getRepository(Server::class)->find($id);
    }
    
    public function countServersPlayers(): array
    {
        $counters = $this->entityManager->getRepository(Server::class)->countServersPlayers();
        $result = [];
        foreach ($counters as $counter) {
            $result[$counter['id']] = $counter['player_count'];
        }
        return $result;
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
    
    public function create(string $name, string $description, string $banner, string $startedAt, int $machineId, string $subDomain = null, array $factions, string $type): Server
    {
        if (($machine = $this->machineManager->get($machineId)) === null) {
            throw new NotFoundHttpException('game.server.machine_not_found');
        }
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
            ->setDescription($description)
            ->setBanner($banner)
            ->setSignature($signature)
            ->setStartedAt(new \DateTime($startedAt))
            ->setMachine($machine)
        ;
        if ($subDomain !== null) {
            $server->setSubDomain($subDomain);
        }
        foreach ($factions as $factionId) {
            if (($faction = $this->factionManager->get($factionId)) === null) {
                throw new NotFoundHttpException('game.server.faction_not_found');
            }
            $server->addFaction($faction);
        }
        $response = $this->serverGateway->bindServer($server->getHost(), $this->rsaEncryptionManager->encrypt($server, json_encode([
            'name' => $name,
            'type' => $type,
            'factions' => $server->getFactions()->toArray(),
            'signature' => $signature,
            'map_size' => 100,
        ])));
        if ($response->getStatusCode() !== 201) {
            throw new \ErrorException($response->getBody()->getContents());
        }
        $this->entityManager->persist($server);
        $this->entityManager->flush($server);
        return $server;
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
        
        $jwt = $this->rsaEncryptionManager->decrypt(
            $response->getHeader('Application-Key')[0],
            $response->getHeader('Application-Iv')[0],
            $response->getBody()->getContents()
        );
        
        if (!$user->hasServer($server)) {
            $user->addServer($server);
            $this->entityManager->flush($user);
        }
        return $jwt;
    }
}