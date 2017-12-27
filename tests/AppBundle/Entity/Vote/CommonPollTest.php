<?php

namespace Tests\AppBundle\Model\Vote;

use PHPUnit\Framework\TestCase;

use AppBundle\Entity\Vote\CommonPoll;

class CommonPollTest extends TestCase
{
    public function testModel()
    {
        $poll =
            (new CommonPoll())
            ->setId(1)
            ->setTitle('On mange où ?')
            ->setContent('KFC')
            ->setCreatedAt(new \DateTime())
            ->setEndedAt(new \DateTime('+7days'))
            ->setIsOver(true)
            ->setIsApproved(false)
        ;
        $this->assertEquals(1, $poll->getId());
        $this->assertEquals('On mange où ?', $poll->getTitle());
        $this->assertEquals('KFC', $poll->getContent());
        $this->assertInstanceOf('DateTime', $poll->getCreatedAt());
        $this->assertInstanceOf('DateTime', $poll->getEndedAt());
        $this->assertTrue($poll->getIsOver());
        $this->assertFalse($poll->getIsApproved());
    }
    
    public function testPrePersist()
    {
        $poll = new CommonPoll();
        $poll->prePersist();
        
        $this->assertInstanceOf('DateTime', $poll->getCreatedAt());
        $this->assertFalse($poll->getIsOver());
        $this->assertFalse($poll->getIsApproved());
    }
    
    public function testGetType()
    {
        $featurePoll = new CommonPoll();
        $this->assertEquals(CommonPoll::TYPE_COMMON, $featurePoll->getType());
    }
}
