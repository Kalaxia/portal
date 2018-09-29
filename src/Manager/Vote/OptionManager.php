<?php

namespace App\Manager\Vote;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Vote\{
    Option,
    Poll
};

class OptionManager
{
    /** @var EntityManagerInterface **/
    protected $entityManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @param int $id
     * @return Option
     */
    public function get($id)
    {
        return $this->entityManager->getRepository(Option::class)->find($id);
    }
    
    /**
     * @param Poll $poll
     * @return array
     */
    public function getPollOptions(Poll $poll)
    {
        return $this->entityManager->getRepository(Option::class)->findByPoll($poll);
    }
}