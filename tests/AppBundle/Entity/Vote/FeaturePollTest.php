<?php

namespace Tests\App\Model\Vote;

use PHPUnit\Framework\TestCase;

use App\Entity\Vote\{
    FeaturePoll,
    Option
};
use App\Model\Project\Feedback;

class FeaturePollTest extends TestCase
{
    public function testModel()
    {
        $poll =
            (new FeaturePoll())
            ->setId(1)
            ->setFeedback(new Feedback())
            ->setFeedbackId('qs56d4f5sd5gd56ds6')
            ->setCreatedAt(new \DateTime())
            ->setEndedAt(new \DateTime(FeaturePoll::POLL_DURATION))
            ->setIsOver(true)
            ->setScore(10)
            ->setNbVotes(12)
            ->setWinningOption((new Option()))
        ;
        $this->assertEquals(1, $poll->getId());
        $this->assertInstanceOf(Feedback::class, $poll->getFeedback());
        $this->assertEquals('qs56d4f5sd5gd56ds6', $poll->getFeedbackId());
        $this->assertInstanceOf('DateTime', $poll->getCreatedAt());
        $this->assertInstanceOf('DateTime', $poll->getEndedAt());
        $this->assertTrue($poll->getIsOver());
        $this->assertEquals(10, $poll->getScore());
        $this->assertEquals(12, $poll->getNbVotes());
        $this->assertInstanceOf(Option::class, $poll->getWinningOption());
    }
    
    public function testPrePersist()
    {
        $poll = new FeaturePoll();
        $poll->prePersist();
        
        $this->assertInstanceOf('DateTime', $poll->getCreatedAt());
        $this->assertFalse($poll->getIsOver());
    }
    
    public function testGetType()
    {
        $featurePoll = new FeaturePoll();
        $this->assertEquals(FeaturePoll::TYPE_FEATURE, $featurePoll->getType());
    }
}
