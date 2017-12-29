<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Vote\Poll;
use AppBundle\Entity\Vote\Option;
use AppBundle\Manager\Vote\PollManager;
use AppBundle\Manager\Vote\VoteManager;

use AppBundle\Model\Project\Feedback;
use AppBundle\Gateway\FeedbackGateway;

class ProcessVoteResultsCommand extends ContainerAwareCommand
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    /** @var PollManager **/
    protected $pollManager;
    /** @var VoteManager **/
    protected $voteManager;
    /** @var FeedbackGateway **/
    protected $feedbackGateway;
    
    protected function configure()
    {
        $this
            ->setName('app:vote:process-results')
            ->setDescription('Test encryption between portal and game server')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->pollManager = $this->getContainer()->get(PollManager::class);
        $this->voteManager = $this->getContainer()->get(VoteManager::class);
        $this->feedbackGateway = $this->getContainer()->get(FeedbackGateway::class);
        
        $polls = $this->pollManager->getActivePolls();
        
        foreach ($polls as $poll) {
            if ($poll->getEndedAt() <= new \DateTime()) {
                $this->processResults($poll);
            }
        }
        
    }

    protected function processResults(Poll $poll)
    {
        $votes = $this->voteManager->getPollVotes($poll);
        
        $scores = $options = [];
        
        foreach ($votes as $vote) {
            $value = $vote->getOption()->getValue();
            // We store the option object to set the winning one to the poll later
            if (!isset($options[$value])) {
                $options[$value] = $vote->getOption();
            }
            $scores[$value] =
                (isset($scores[$value]))
                ? $scores[$value] + 1
                : 1
            ;
        }
        $poll
            ->setIsOver(true)
            ->setNbVotes(count($votes))
        ;
        arsort($scores);
        reset($scores);
        if ($poll->getNbVotes() > 0) {
            $key = key($scores);
            $poll
                ->setWinningOption($options[$key])
                ->setScore($scores[$key])
            ;
        }
        if ($poll->getType() === Poll::TYPE_FEATURE && $poll->getWinningOption()->getValue() === Option::VALUE_YES) {
            $feature = $poll->getFeedback();
            $feature->setStatus(Feedback::STATUS_READY);
            $this->feedbackGateway->updateEvolution($feature);
        }
        $this->entityManager->flush($poll);
    }
}
