<?php

namespace Tests\App\Manager\Vote;

use PHPUnit\Framework\TestCase;

use App\Manager\Vote\PollManager;

use App\Model\Project\Feedback;
use App\Entity\Vote\FeaturePoll;

class PollManagerTest extends TestCase
{
    /** @var PollManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new PollManager(
            $this->getEntityManagerMock(),
            $this->getFeedbackManagerMock()
        );
    }
    
    public function testCreateFeaturePoll()
    {
        $poll = $this->manager->createFeaturePoll($this->getFeedbackMock('abcde'));
        
        $this->assertInstanceOf(FeaturePoll::class, $poll);
        $this->assertInstanceOf(Feedback::class, $poll->getFeedback());
        $this->assertEquals('abcde', $poll->getFeedback()->getId());
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage project.votes.already_accepted
     */
    public function testCreateFeaturePollFromInvalidFeedback()
    {
        $this->manager->createFeaturePoll($this->getFeedbackMock('invalid_status'));
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage project.votes.already_voting
     */
    public function testCreateFeaturePollWithExistingPoll()
    {
        $this->manager->createFeaturePoll($this->getFeedbackMock('existing_poll'));
    }
    
    public function testGet()
    {
        $poll = $this->manager->get(10);
        
        $this->assertInstanceOf(FeaturePoll::class, $poll);
        $this->assertEquals(10, $poll->getId());
    }
    
    protected function getEntityManagerMock()
    {
        $entityManagerMock = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
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
            ->setMethods(['find', 'getLastFeaturePoll'])
            ->getMock()
        ;
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnCallback([$this, 'getFeaturePollMock'])
        ;
        $repository
            ->expects($this->any())
            ->method('getLastFeaturePoll')
            ->willReturnCallback([$this, 'getLastFeaturePollMock'])
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
        ;
    }
    
    public function getLastFeaturePollMock($feedbackId)
    {
        if ($feedbackId !== 'existing_poll') {
            return null;
        }
        return
            (new FeaturePoll())
            ->setId(1)
            ->setFeedbackId($feedbackId)
            ->setCreatedAt(new \DateTime())
            ->setIsOver(false)
        ;
    }
    
    protected function getFeedbackManagerMock()
    {
        $evolutionManagerMock = $this
            ->getMockBuilder(\App\Manager\Project\FeedbackManager::class)
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
            (new Feedback())
            ->setId($id)
            ->setTitle("J'aime les tartes")
            ->setStatus(($id !== 'invalid_status') ? Feedback::STATUS_TO_SPECIFY : Feedback::STATUS_IN_PROGRESS)
        ;
    }
}