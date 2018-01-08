<?php

namespace Tests\AppBundle\Manager\Game;

use PHPUnit\Framework\TestCase;

use AppBundle\Manager\Game\ServerManager;

use AppBundle\Entity\Game\{
    TutorialServer,
    SoloServer,
    Server,
    MultiplayerServer,
    Faction
};

use AppBundle\Entity\User;

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
        return
            (new Faction())
            ->setId($id)
        ;
    }
    
    public function getServerGatewayMock()
    {
        $serverGatewayMock = $this
            ->getMockBuilder(\AppBundle\Gateway\ServerGateway::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $serverGatewayMock;
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