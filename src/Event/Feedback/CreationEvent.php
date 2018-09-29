<?php

namespace App\Event\Feedback;

use Symfony\Component\EventDispatcher\Event;

use App\Model\Project\Feedback;

class CreationEvent extends Event
{
    const NAME = 'feedback.creation';
    /** @var Feedback **/
    protected $feedback;
    
    /**
     * @param Feedback $feedback
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }
    
    /**
     * @return Feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}