<?php

namespace AppBundle\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Model\Project\Feedback;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Vote\FeaturePollRepository")
 * @ORM\Table(name="vote__feature_polls")
 */
class FeaturePoll extends Poll
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $feedbackId;
    /** @var Feedback **/
    protected $feedback;
    
    const POLL_DURATION = '+2days';
    
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return Poll::TYPE_FEATURE;
    }
    
    /**
     * @param string $feedbackId
     * @return $this
     */
    public function setFeedbackId($feedbackId)
    {
        $this->feedbackId = $feedbackId;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getFeedbackId()
    {
        return $this->feedbackId;
    }
    
    /**
     * @param Feedback $feedback
     * @return $this
     */
    public function setFeedback(Feedback $feedback)
    {
        $this->feedback = $feedback;
        
        return $this;
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
    public function getTitle()
    {
        return $this->feedback->getTitle();
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->feedback->getDescription();
    }
}