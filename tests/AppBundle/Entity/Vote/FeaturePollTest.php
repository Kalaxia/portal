<?php

namespace Tests\AppBundle\Model\Vote;

use PHPUnit\Framework\TestCase;

use AppBundle\Entity\Vote\FeaturePoll;
use AppBundle\Model\Project\Evolution;

class FeaturePollTest extends TestCase
{
    public function testModel()
    {
        $poll =
            (new FeaturePoll())
            ->setId(1)
            ->setFeedback(new Evolution())
            ->setFeedbackId('qs56d4f5sd5gd56ds6')
            ->setCreatedAt(new \DateTime())
            ->setEndedAt(new \DateTime(FeaturePoll::POLL_DURATION))
            ->setIsOver(true)
            ->setIsApproved(true)
        ;
        $this->assertEquals(1, $poll->getId());
        $this->assertInstanceOf(Evolution::class, $poll->getFeedback());
        $this->assertEquals('qs56d4f5sd5gd56ds6', $poll->getFeedbackId());
        $this->assertInstanceOf('DateTime', $poll->getCreatedAt());
        $this->assertInstanceOf('DateTime', $poll->getEndedAt());
        $this->assertTrue($poll->getIsOver());
        $this->assertTrue($poll->getIsApproved());
    }
    
    public function testPrePersist()
    {
        $poll = new FeaturePoll();
        $poll->prePersist();
        
        $this->assertInstanceOf('DateTime', $poll->getCreatedAt());
        $this->assertFalse($poll->getIsOver());
        $this->assertFalse($poll->getIsApproved());
    }
    
    public function testGetType()
    {
        $featurePoll = new FeaturePoll();
        $this->assertEquals(FeaturePoll::TYPE_FEATURE, $featurePoll->getType());
    }
}
