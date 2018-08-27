<?php

namespace AppBundle\Manager\Vote;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Model\Project\Feedback;
use AppBundle\Entity\Vote\{
    FeaturePoll,
    Poll,
    Option
};

use AppBundle\Manager\Project\FeedbackManager;

class PollManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    /** @var FeedbackManager **/
    protected $feedbackManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param FeedbackManager $feedbackManager
     */
    public function __construct(EntityManagerInterface $entityManager, FeedbackManager $feedbackManager)
    {
        $this->entityManager = $entityManager;
        $this->feedbackManager = $feedbackManager;
    }
    
    /**
     * @param Feedback $feedback
     */
    public function createFeaturePoll(Feedback $feedback): FeaturePoll
    {
        if ($feedback->getStatus() !== Feedback::STATUS_TO_SPECIFY) {
            throw new BadRequestHttpException('project.votes.already_accepted');
        }
        if (($featurePoll = $this->getLastFeaturePoll($feedback)) !== null && !$featurePoll->getIsOver()) {
            throw new BadRequestHttpException('project.votes.already_voting');
        }
        $poll = 
            (new FeaturePoll())
            ->setFeedback($feedback)
            ->setFeedbackId($feedback->getId())
            ->setEndedAt((new \DateTime(FeaturePoll::POLL_DURATION)))
        ;
        $yes =
            (new Option())
            ->setPoll($poll)
            ->setValue(Option::VALUE_YES)
        ;
        $no =
            (new Option())
            ->setPoll($poll)
            ->setValue(Option::VALUE_NO)
        ;
        $this->entityManager->persist($poll);
        $this->entityManager->persist($yes);
        $this->entityManager->persist($no);
        $this->entityManager->flush();
        return $poll;
    }
    
    /**
     * @param integer $id
     * @return Poll
     */
    public function get($id)
    {
        $poll = $this->entityManager->getRepository(Poll::class)->find($id);
        if ($poll instanceof FeaturePoll) {
            $poll->setFeedback($this->feedbackManager->get($poll->getFeedbackId()));
        }
        return $poll;
    }
    
    /**
     * @return array
     */
    public function getActivePolls()
    {
        $polls = $this->entityManager->getRepository(Poll::class)->findBy([
            'isOver' => false
        ]);
        foreach ($polls as &$poll) {
            if ($poll instanceof FeaturePoll) {
                $poll->setFeedback($this->feedbackManager->get($poll->getFeedbackId()));
            }
        }
        return $polls;
    }
    
    /**
     * @param Feedback $feedback
     * @return Poll
     */
    public function getLastFeaturePoll(Feedback $feedback)
    {
        return $this
            ->entityManager
            ->getRepository(FeaturePoll::class)
            ->getLastFeaturePoll($feedback->getId())
        ;
    }
}