<?php

namespace AppBundle\Event\Feedback;

use Symfony\Component\EventDispatcher\Event;

use AppBundle\Model\Project\Feedback;
use AppBundle\Entity\User;

class ValidateEvent extends Event
{
    const NAME = 'feedback.validate';
    /** @var Feedback **/
    protected $feedback;
    /** @var User **/
    protected $user;
    
    public function __construct(Feedback $feedback, User $user)
    {
        $this->feedback = $feedback;
        $this->user = $user;
    }
    
    public function getFeedback(): Feedback
    {
        return $this->feedback;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
}