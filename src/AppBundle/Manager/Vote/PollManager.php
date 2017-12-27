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
    /** @var TranslatorInterface **/
    protected $translator;
    /** @var EvolutionManager **/
    protected $evolutionManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param EvolutionManager $evolutionManager
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, EvolutionManager $evolutionManager)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
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
            ->setColor("#00FF00")
            ->setValue($this->translator->trans('project.votes.yes'))
        ;
        $no =
            (new Option())
            ->setPoll($poll)
            ->setColor("#FF0000")
            ->setValue($this->translator->trans('project.votes.no'))
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