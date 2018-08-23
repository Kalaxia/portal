<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Manager\UserManager;

use AppBundle\Entity\User;

use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    /** @var UserManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new UserManager($this->getEntityManagerMock());
    }
    
    public function testGetLastUsers()
    {
        $this->assertEquals($this->getUsersMock(), $this->manager->getLastUsers());
    }
    
    public function testGetAll()
    {
        $this->assertEquals($this->getUsersMock(), $this->manager->getAll());
    }
    
    public function testSearch()
    {
        $this->assertEquals($this->getUsersMock(), $this->manager->search([
            'username' => 'T'
        ]));
    }
    
    protected function getEntityManagerMock()
    {
        $entityManagerMock = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        
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
            ->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLastUsers', 'findAll', 'searchUsers'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('getLastUsers')
            ->willReturnCallback([$this, 'getUsersMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findAll')
            ->willReturnCallback([$this, 'getUsersMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('searchUsers')
            ->willReturnCallback([$this, 'getUsersMock'])
        ;
        return $repositoryMock;
    }
    
    public function getUsersMock()
    {
        return [
            (new User())
            ->setId(1)
            ->setUsername('Toto'),
            (new User())
            ->setId(2)
            ->setUsername('Tata'),
            (new User())
            ->setId(3)
            ->setUsername('Tutu'),
        ];
    }
}