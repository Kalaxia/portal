<?php

namespace App\Event\Feedback;

use Symfony\Component\EventDispatcher\Event;

use App\Model\Project\Feedback;

class UpdateEvent extends Event
{
    const NAME = 'feedback.update';
    /** @var Feedback **/
    protected $feedback;
    /** @var string **/
    protected $oldStatus;
    
    /**
     * @param Feedback $feedback
     * @param string $oldStatus
     */
    public function __construct(Feedback $feedback, $oldStatus)
    {
        $this->feedback = $feedback;
        $this->oldStatus = $oldStatus;
    }
    
    /**
     * @return Feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
    
    /**
     * @return string
     */
    public function getOldStatus()
    {
        return $this->oldStatus;
    }
}