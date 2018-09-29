<?php

namespace App\Event\Feedback;

use Symfony\Component\EventDispatcher\Event;

use App\Model\Project\Feedback;
use App\Model\Project\Comment;

class CommentCreationEvent extends Event
{
    const NAME = 'feedback.comment_creation';
    /** @var Feedback **/
    protected $feedback;
    /** @var Comment **/
    protected $comment;
    
    /**
     * @param Feedback $feedback
     * @param Comment $comment
     */
    public function __construct(Feedback $feedback, Comment $comment)
    {
        $this->feedback = $feedback;
        $this->comment = $comment;
    }
    
    /**
     * @return Feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
    
    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }
}