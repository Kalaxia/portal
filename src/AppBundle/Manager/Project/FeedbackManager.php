<?php

namespace AppBundle\Manager\Project;

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
    
    public function getBoardFeedbacks()
    {
        $bugs = $this->bugManager->getAll();
        $evolutions = $this->evolutionManager->getAll();
        
        $results = [];
        
        foreach ($bugs as $bug) {
            $results[$bug->getStatus()][] = $bug;
        }
        foreach ($evolutions as $evolution) {
            $results[$evolution->getStatus()][] = $evolution;
        }
        return $results;
    }
}