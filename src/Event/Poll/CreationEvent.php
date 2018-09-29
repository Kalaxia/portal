<?php

namespace App\Event\Poll;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Vote\Poll;

class CreationEvent extends Event
{
    const NAME = 'poll.creation';
    /** @var Poll **/
    protected $poll;
    
    /**
     * @param Poll $poll
     */
    public function __construct(Poll $poll)
    {
        $this->poll = $poll;
    }
    
    /**
     * @return Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }
}