<?php

namespace AppBundle\Manager\Project;

use AppBundle\Model\Project\Feedback;

class FeedbackManager
{
    /** @var BugManager **/
    protected $bugManager;
    /** @var EvolutionManager **/
    protected $evolutionManager;
    
    /**
     * @param \AppBundle\Manager\Project\BugManager $bugManager
     * @param \AppBundle\Manager\Project\EvolutionManager $evolutionManager
     */
    public function __construct(BugManager $bugManager, EvolutionManager $evolutionManager)
    {
        $this->bugManager = $bugManager;
        $this->evolutionManager = $evolutionManager;
    }
    
    /**
     * @param string $id
     * @param string $type
     * @return Feedback
     */
    public function getFeedback($id, $type)
    {
        return
            ($type === Feedback::TYPE_BUG)
            ? $this->bugManager->get($id)
            : $this->evolutionManager->get($id)
        ;
    }
    
    /**
     * @return array
     */
    public function getBoardFeedbacks()
    {
        $bugs = $this->bugManager->getAll();
        $evolutions = $this->evolutionManager->getAll();
        
        $results = [];
        
        foreach ($bugs as $bug) {
            $results[$bug->getStatus()][-$bug->getUpdatedAt()->getTimestamp()] = $bug;
        }
        foreach ($evolutions as $evolution) {
            $results[$evolution->getStatus()][-$evolution->getUpdatedAt()->getTimestamp()] = $evolution;
        }
        foreach (Feedback::getStatuses() as $status) {
            if (!isset($results[$status])) {
                continue;
            }
            ksort($results[$status]);
        }
        return $results;
    }
}