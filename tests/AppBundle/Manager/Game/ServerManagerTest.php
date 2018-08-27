<?php

namespace Tests\AppBundle\Manager\Game;

use PHPUnit\Framework\TestCase;

use AppBundle\Manager\Game\ServerManager;

use AppBundle\Entity\Game\{
    TutorialServer,
    SoloServer,
    Server,
    MultiplayerServer,
    Faction,
    Machine
};

use AppBundle\Entity\User;

use GuzzleHttp\Psr7\Response;

class ServerManagerTest extends TestCase
{
    /** @var ServerManager **/
    protected $manager;
    /** @var array **/
    protected $servers;
    
    public function setUp()
    {
        $this->manager = new ServerManager(
            $this->getEntityManagerMock(),
            $this->getFactionManagerMock(),
            $this->getMachineManagerMock(),
            $this->getServerGatewayMock(),
            $this->getRsaEncryptionManagerMock(),
            $this->getSluggerMock()
        );
    }
    
    public function testGet()
    {
        $server = $this->manager->get(15);
        
        $this->assertInstanceOf(SoloServer::class, $server);
        $this->assertEquals(15, $server->getId());
    }
    
    public function testGetAvailableServers()
    {
        $mockedServers = $this->getServersListMock();
        $user =
            (new User())
            ->addServer($mockedServers[0])
            ->addServer($mockedServers[1])
        ;
        $servers = $this->manager->getAvailableServers($user);
        
        $this->assertCount(1, $servers);
        $this->assertInstanceOf(MultiplayerServer::class, $servers[0]);
        $this->assertEquals(25, $servers[0]->getId());
    }
    
    public function testGetOpenedServers()
    {
        $servers = $this->manager->getOpenedServers();
        
        $this->assertCount(3, $servers);
    }
    
    public function testGetNextServers()
    {
        $this->assertCount(2, $this->manager->getNextServers());
    }
    
    public function testCreateServer()
    {
        $server = $this->manager->create(
            'Serveur local',
            'Mon super serveur',
            'local_server.png',
            '+2 days',
            1,
            null,
            [1, 2, 3],
            Server::TYPE_MULTIPLAYER
        );
        $this->assertInstanceOf(Server::class, $server);
        $this->assertEquals('Serveur local', $server->getName());
        $this->assertEquals('Mon super serveur', $server->getDescription());
        $this->assertEquals('local_server.png', $server->getBanner());
        $this->assertInstanceOf(\DateTime::class, $server->getStartedAt());
        $this->assertEquals(1, $server->getMachine()->getId());
        $this->assertTrue($server->getMachine()->getIsLocal());
        $this->assertCount(3, $server->getFactions());
        $this->assertEquals(Server::TYPE_MULTIPLAYER, $server->getType());
    }
    
    public function testCreateServerWithRemoteMachine()
    {
        $server = $this->manager->create(
            'Serveur distant',
            'Un serveur loin, très loin',
            'remote_server.png',
            '+1 days',
            2,
            'test',
            [1, 2, 3],
            Server::TYPE_MULTIPLAYER
        );
        $this->assertInstanceOf(Server::class, $server);
        $this->assertEquals(2, $server->getMachine()->getId());
        $this->assertEquals('test.kalaxia_nginx', $server->getHost());
        $this->assertFalse($server->getMachine()->getIsLocal());
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage game.server.machine_not_found
     */
    public function testCreateServerWithInvalidMachine()
    {
        $this->manager->create('Serveur imaginaire', 'Un serveur abstrait', 'unknown_server.png', '+15 days', 3, null, [1, 2, 3], Server::TYPE_SOLO);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage game.server.faction_not_found
     */
    public function testCreateServerWithInvalidFaction()
    {
        $this->manager->create('Serveur fou', 'Le serveur des mille nations', 'faction_server.png', '+1 hour', 1, null, [4, 5, 6], Server::TYPE_MULTIPLAYER);
    }
    
    protected function getEntityManagerMock()
    {
        $entityManagerMock = $this
            ->getMockBuilder(\Doctrine\ORM\EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback([$this, 'getRepositoryMock'])
        ;
        return $entityManagerMock;
    }
    
    public function getRepositoryMock()
    {
        $repositoryMock = $this
            ->getMockBuilder(\AppBundle\Repository\Game\ServerRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('find')
            ->willReturnCallback([$this, 'getServerMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('getOpenedServers')
            ->willReturnCallback([$this, 'getServersListMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('getNextServers')
            ->willReturnCallback([$this, 'getNextServersListMock'])
        ;
        return $repositoryMock;
    }
    
    public function getServerMock($id)
    {
        return
            (new SoloServer())
            ->setId($id)
        ;
    }
    
    public function getServersListMock()
    {
        if ($this->servers !== null) {
            return $this->servers;
        }
        $this->servers = [
            (new TutorialServer())->setId(12),
            (new SoloServer())->setId(17),
            (new MultiplayerServer())->setId(25)
        ];
        return $this->servers;
    }
    
    public function getNextServersListMock()
    {
        return [
            (new MultiplayerServer())->setId(4),
            (new MultiplayerServer())->setId(5)
        ];
    }
    
    public function getFactionManagerMock()
    {
        $factionManagerMock = $this
            ->getMockBuilder(\AppBundle\Manager\Game\FactionManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $factionManagerMock
            ->expects($this->any())
            ->method('get')
            ->willReturnCallback([$this, 'getFactionMock'])
        ;
        return $factionManagerMock;
    }
    
    public function getFactionMock($id)
    {
        if ($id > 3) {
            return null;
        }
        return
            (new Faction())
            ->setId($id)
        ;
    }
    
    public function getMachineManagerMock()
    {
        $machineManagerMock = $this
            ->getMockBuilder(\AppBundle\Manager\Game\MachineManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $machineManagerMock
            ->expects($this->any())
            ->method('get')
            ->willReturnCallback([$this, 'getMachineMock'])
        ;
        return $machineManagerMock;
    }
    
    public function getMachineMock($id)
    {
        if ($id > 2) {
            return null;
        }
        return
            (new Machine())
            ->setId($id)
            ->setName('Local')
            ->setSlug('local')
            ->setHost('kalaxia_nginx')
            ->setIsLocal(($id === 1))
        ;
    }
    
    public function getServerGatewayMock()
    {
        $serverGatewayMock = $this
            ->getMockBuilder(\AppBundle\Gateway\ServerGateway::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $serverGatewayMock
            ->expects($this->any())
            ->method('bindServer')
            ->willReturnCallback([$this, 'getServerResponseMock'])
        ;
        return $serverGatewayMock;
    }
    
    public function getServerResponseMock()
    {
        $responseMock = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $responseMock
            ->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(201)
        ;
        return $responseMock;
    }
    
    public function getRsaEncryptionManagerMock()
    {
        $rsaEncryptionManagerMock = $this
            ->getMockBuilder(\AppBundle\Security\RsaEncryptionManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $rsaEncryptionManagerMock;
    }
    
    public function getSluggerMock()
    {
        $sluggerMock = $this
            ->getMockBuilder(\AppBundle\Utils\Slugger::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $sluggerMock;
    }
}