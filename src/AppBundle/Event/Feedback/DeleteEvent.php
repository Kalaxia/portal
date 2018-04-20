<?php

namespace AppBundle\Event\Feedback;

use Symfony\Component\EventDispatcher\Event;

use AppBundle\Model\Project\Feedback;

class DeleteEvent extends Event
{
    const NAME = 'feedback.delete';
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