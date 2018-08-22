<?php

namespace Tests\AppBundle\Manager;

use PHPUnit\Framework\TestCase;

use AppBundle\Manager\NotificationManager;

use AppBundle\Entity\Notification;
use AppBundle\Entity\User;

class NotificationManagerTest extends TestCase
{
    /** @var NotificationManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new NotificationManager($this->getEntityManagerMock());
    }
    
    public function testCreate()
    {
        $notification = $this->manager->create($this->getUserMock(1), 'Good news !!', 'An incredible news');
        
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertInstanceOf(User::class, $notification->getUser());
        $this->assertEquals('Good news !!', $notification->getTitle());
        $this->assertEquals('An incredible news', $notification->getContent());
    }
    
    public function testRead()
    {
        $this->assertNull($this->manager->read(1, $this->getUserMock(1)));
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage notifications.not_found
     */
    public function testReadUnexistingNotification()
    {
        $this->manager->read(2, $this->getUserMock(1));
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @expectedExceptionMessage notifications.access_denied
     */
    public function testReadOtherUserNotification()
    {
        $this->manager->read(1, $this->getUserMock(2));
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
            ->willReturnCallback([$this, 'getNotificationMock'])
        ;
        return $repositoryMock;
    }
    
    public function getNotificationMock($id)
    {
        if ($id !== 1) {
            return null;
        }
        return
            (new Notification())
            ->setId($id)
            ->setTitle('New message')
            ->setContent('You have a new message')
            ->setUser($this->getUserMock(1))
            ->setIsRead(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
        ;
    }
    
    public function getUserMock($id)
    {
        return
            (new User())
            ->setId($id)
        ;
    }
}