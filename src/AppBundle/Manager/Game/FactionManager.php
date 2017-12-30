<?php

namespace AppBundle\Manager\Game;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Game\Faction;

class FactionManager
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
     * @return array
     */
    public function getAll()
    {
        return $this->entityManager->getRepository(Faction::class)->findAll();
    }
    
    /**
     * @param string $name
     * @param string $description
     * @return Faction
     */
    public function create($name, $description)
    {
        $faction =
            (new Faction())
            ->setName($name)
            ->setDescription($description)
        ;
        $this->entityManager->persist($faction);
        $this->entityManager->flush($faction);
        return $faction;
    }
}