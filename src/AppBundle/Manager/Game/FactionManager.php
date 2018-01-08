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
     * @param int $id
     * @return Faction
     */
    public function get($id)
    {
        return $this->entityManager->getRepository(Faction::class)->find($id);
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
     * @param string $color
     * @return Faction
     */
    public function create($name, $description, $color)
    {
        $faction =
            (new Faction())
            ->setName($name)
            ->setDescription($description)
            ->setColor($color)
        ;
        $this->entityManager->persist($faction);
        $this->entityManager->flush($faction);
        return $faction;
    }
}