<?php

namespace Tests\AppBundle\Model;

use PHPUnit\Framework\TestCase;
use AppBundle\Model\Notification;
use AppBundle\Entity\User;

class NotificationTest extends TestCase
{

  public function testModel()
   {
      $notification = new Notification();

      $notification->setId(2);
      $notification->setUser(new User());
      $notification->setTitle('Nouveau message');
      $notification->setContent('Nouveau contenu') ;
      $notification->setisRead(false);
      $notification->setcreatedAt(new \DateTime);
      $notification->setupdatedAt(new \DateTime);


      $this->assertEquals(2, $notification->getId());
      $this->assertInstanceOf(User::class, $notification->getUser());
      $this->assertEquals('Nouveau message', $notification->getTitle());
      $this->assertEquals('Nouveau contenu', $notification->getContent());
	  $this->assertEquals(false,$notification->getisRead());
	  $this->assertInstanceOf(\DateTime::class, $notification->getcreatedAt());
	  $this->assertInstanceOf(\DateTime::class, $notification->getupdatedAt());

   }

 

}

