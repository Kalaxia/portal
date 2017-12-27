<?php

namespace Tests\AppBundle\Manager\Vote;

use PHPUnit\Framework\TestCase;

use AppBundle\Manager\Vote\PollManager;

use AppBundle\Model\Project\Evolution;
use AppBundle\Entity\Vote\FeaturePoll;

class PollManagerTest extends TestCase
{
    /** @var PollManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new PollManager(
            $this->getEntityManagerMock(),
            $this->getTranslatorMock(),
            $this->getEvolutionManagerMock()
        );
    }
    
    public function testCreateFeaturePoll()
    {
        $poll = $this->manager->createFeaturePoll((new Evolution())->setId('abcd'));
        
        $this->assertInstanceOf(FeaturePoll::class, $poll);
    }
    
    public function testGet()
    {
        $poll = $this->manager->get(10);
        
        $this->assertInstanceOf(FeaturePoll::class, $poll);
        $this->assertEquals(10, $poll->getId());
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
            ->method('persist')
            ->willReturn(true)
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('flush')
            ->willReturn(true)
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
        $repository = $this
            ->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnCallback([$this, 'getFeaturePollMock'])
        ;
        return $repository;
    }
    
    public function getFeaturePollMock($id)
    {
        return
            (new FeaturePoll())
            ->setId($id)
            ->setFeedbackId('abcde')
            ->setCreatedAt(new \DateTime())
            ->setIsOver(false)
            ->setIsApproved(false)
        ;
    }
    
    protected function getTranslatorMock()
    {
        $translatorMock = $this
            ->getMockBuilder(\Symfony\Component\Translation\Translator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $translatorMock;
    }
    
    protected function getEvolutionManagerMock()
    {
        $evolutionManagerMock = $this
            ->getMockBuilder(\AppBundle\Manager\Project\EvolutionManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $evolutionManagerMock
            ->expects($this->any())
            ->method('get')
            ->willReturnCallback([$this, 'getFeedbackMock'])
        ;
        return $evolutionManagerMock;
    }
    
    public function getFeedbackMock($id)
    {
        return
            (new Evolution())
            ->setId($id)
            ->setTitle("J'aime les tartes")
        ;
    }
}