<?php

namespace Tests\App\Manager\Game;

use App\Manager\Game\FactionManager;

use App\Entity\Game\Faction;

use PHPUnit\Framework\TestCase;

class FactionManagerTest extends TestCase
{
    /** @var FactionManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new FactionManager($this->getEntityManagerMock());
    }
    
    public function testGet()
    {
        $faction = $this->manager->get(2);
        
        $this->assertInstanceOf(Faction::class, $faction);
        $this->assertEquals('Les Kalankars', $faction->getName());
        $this->assertEquals('Héritiers d\'Esdrine', $faction->getDescription());
        $this->assertEquals('#0000A0', $faction->getColor());
        $this->assertEquals('kalankars.png', $faction->getBanner());
        
    }
    
    public function testGetAll()
    {
        $this->assertCount(3, $this->manager->getAll());
    }
    
    public function testCreate()
    {
        $faction = $this->manager->create('Ascendance Valkar', 'Un empire implacable', '#A00000', 'valkar_ascendency.png');
        
        $this->assertInstanceOf(Faction::class, $faction);
        $this->assertEquals('Ascendance Valkar', $faction->getName());
        $this->assertEquals('Un empire implacable', $faction->getDescription());
        $this->assertEquals('#A00000', $faction->getColor());
        $this->assertEquals('valkar_ascendency.png', $faction->getBanner());
    }
    
    protected function getEntityManagerMock()
    {
        $entityManagerMock = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $entityManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback([$this, 'getRepositoryMock'])
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true)
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('flush')
            ->willReturn(true)
        ;
        return $entityManagerMock;
    }
    
    public function getRepositoryMock()
    {
        $repositoryMock = $this
            ->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('find')
            ->willReturnCallback([$this, 'getFactionMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findAll')
            ->willReturnCallback([$this, 'getFactionsMock'])
        ;
        return $repositoryMock;
    }
    
    public function getFactionMock()
    {
        return
            (new Faction())
            ->setId(2)
            ->setName('Les Kalankars')
            ->setDescription('Héritiers d\'Esdrine')
            ->setColor('#0000A0')
            ->setBanner('kalankars.png')
        ;
    }
    
    public function getFactionsMock()
    {
        return [
            (new Faction())
            ->setId(1)
            ->setName('Les Adranites')
            ->setDescription('L\'essaim éternel')
            ->setColor('#E1BB81')
            ->setBanner('adranites.png'),
            (new Faction())
            ->setId(2)
            ->setName('Les Kalankars')
            ->setDescription('Héritiers d\'Esdrine')
            ->setColor('#0000A0')
            ->setBanner('kalankars.png'),
            (new Faction())
            ->setId(3)
            ->setName('Ascendance Valkar')
            ->setDescription('Un empire implacable')
            ->setColor('#A00000')
            ->setBanner('valkar_ascendency.png'),
        ];
    }
}