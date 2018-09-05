<?php

namespace Tests\AppBundle\Manager\Vote;

use PHPUnit\Framework\TestCase;

use AppBundle\Manager\Vote\VoteManager;

use AppBundle\Entity\Vote\Vote;
use AppBundle\Entity\Vote\FeaturePoll;
use AppBundle\Entity\Vote\Option;
use AppBundle\Entity\User;

class VoteManagerTest extends TestCase
{
    /** @var VoteManager **/
    protected $manager;
    /** @var Poll **/
    protected $poll;
    
    public function setUp()
    {
        $this->manager = new VoteManager($this->getEntityManagerMock(), $this->getOptionManagerMock());
        $this->poll = null;
    }
    
    public function testGetPollVotes()
    {
        $votes = $this->manager->getPollVotes($this->getPollMock());
        
        $this->assertCount(3, $votes);
    }
    
    public function testVote()
    {
        $this->poll = $this->getPollMock();
        $vote = $this->manager->vote($this->poll, $this->getUserMock(), 1);
        
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->getPoll(), $this->poll);
        $this->assertEquals($vote->getOption()->getId(), 1);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage polls.not_found_option
     */
    public function testVoteWithUnknownOption()
    {
        $this->manager->vote($this->getPollMock(), $this->getUserMock(), 2);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage polls.invalid_option
     */
    public function testVoteWithInvalidOption()
    {
        $this->manager->vote($this->getPollMock(), $this->getUserMock(), 3);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage polls.user_already_voted
     */
    public function testVoteTwice()
    {
        $this->poll = $this->getPollMock();
        $this->manager->vote($this->poll, $this->getUserMock(true), 1);
    }
    
    public function testHasAlreadyVoted()
    {
        $this->assertFalse($this->manager->hasAlreadyVoted($this->getPollMock(), $this->getUserMock()));
        $this->assertTrue($this->manager->hasAlreadyVoted($this->getPollMock(), $this->getUserMock(true)));
    }
    
    public function getEntityManagerMock()
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
            ->setMethods(['findByPoll', 'findOneBy'])
            ->getMock()
        ;
        $repository
            ->expects($this->any())
            ->method('findByPoll')
            ->willReturnCallback([$this, 'getVotesMock'])
        ;
        $repository
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturnCallback([$this, 'getVoteMock'])
        ;
        return $repository;
    }
    
    public function getOptionManagerMock()
    {
        $optionManagerMock = $this
            ->getMockBuilder(\AppBundle\Manager\Vote\OptionManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $optionManagerMock
            ->expects($this->any())
            ->method('get')
            ->willReturnCallback([$this, 'getOptionMock'])
        ;
        return $optionManagerMock;
    }
    
    public function getPollMock()
    {
        return
            (new FeaturePoll())
        ;
    }
    
    public function getUserMock($doublon = false)
    {
        return
            (new User())
            ->setId(($doublon === false ? 1 : 2))
        ;
    }
    
    public function getOptionMock($id)
    {
        if ($id === 2) {
            return null;
        }
        return
            (new Option())
            ->setId($id)
            ->setPoll(($id !== 3 && $this->poll !== null) ? $this->poll : $this->getPollMock())
        ;
    }
    
    public function getVoteMock($criterias)
    {
        if ($criterias['user']->getId() === 1) {
            return null;
        }
        return
            (new Vote())
        ;
    }
    
    public function getVotesMock()
    {
        return [
            (new Vote()),
            (new Vote()),
            (new Vote())
        ];
    }
}