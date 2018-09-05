<?php

namespace AppBundle\Manager\Vote;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\User;

use AppBundle\Entity\Vote\{
    Poll,
    Vote
};

class VoteManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    /** @var OptionManager **/
    protected $optionManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param OptionManager $optionManager
     */
    public function __construct(EntityManagerInterface $entityManager, OptionManager $optionManager)
    {
        $this->entityManager = $entityManager;
        $this->optionManager = $optionManager;
    }
    
    public function getPollVotes(Poll $poll)
    {
        return $this->entityManager->getRepository(Vote::class)->findByPoll($poll);
    }
    
    /**
     * @param Poll $poll
     * @param User $user
     * @param int $optionId
     * @return Vote
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     * @throws BadRequestHttpexception
     */
    public function vote(Poll $poll, User $user, $optionId)
    {
        if (($option = $this->optionManager->get($optionId)) === null) {
            throw new NotFoundHttpException('polls.not_found_option');
        }
        if ($option->getPoll() !== $poll) {
            throw new BadRequestHttpException('polls.invalid_option');
        }
        if ($this->hasAlreadyVoted($poll, $user)) {
            throw new BadRequestHttpexception('polls.user_already_voted');
        }
        $vote =
            (new Vote())
            ->setPoll($poll)
            ->setUser($user)
            ->setOption($option)
        ;
        $this->entityManager->persist($vote);
        $this->entityManager->flush($vote);
        
        return $vote;
    }
    
    /**
     * @param Poll $poll
     * @param User $user
     * @return boolean
     */
    public function hasAlreadyVoted(Poll $poll, User $user)
    {
        return $this->entityManager->getRepository(Vote::class)->findOneBy([
            'poll' => $poll,
            'user' => $user
        ]) !== null;
    }
}