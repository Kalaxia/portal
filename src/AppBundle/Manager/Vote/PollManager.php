<?php

namespace AppBundle\Manager\Vote;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;

use AppBundle\Model\Project\Feedback;
use AppBundle\Entity\Vote\{
    FeaturePoll,
    Poll,
    Option
};

use AppBundle\Manager\Project\EvolutionManager;

class PollManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    /** @var EvolutionManager **/
    protected $evolutionManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param EvolutionManager $evolutionManager
     */
    public function __construct(EntityManagerInterface $entityManager, EvolutionManager $evolutionManager)
    {
        $this->entityManager = $entityManager;
        $this->evolutionManager = $evolutionManager;
    }
    
    /**
     * @param Feedback $feedback
     */
    public function createFeaturePoll(Feedback $feedback)
    {
        if ($feedback->getStatus() !== Feedback::STATUS_TO_SPECIFY) {
            throw new BadRequestHttpException('project.votes.already_accepted');
        }
        if ($this->getActivePollByFeature($feedback) !== null) {
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
            $poll->setFeedback($this->evolutionManager->get($poll->getFeedbackId()));
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
                $poll->setFeedback($this->evolutionManager->get($poll->getFeedbackId()));
            }
        }
        return $polls;
    }
    
    /**
     * @param Feedback $feedback
     * @return Poll
     */
    public function getActivePollByFeature(Feedback $feedback)
    {
        return $this->entityManager->getRepository(FeaturePoll::class)->findOneBy([
            'feedbackId' => $feedback->getId(),
            'isOver' => false
        ]);
    }
}