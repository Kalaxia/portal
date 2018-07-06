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
    
    public function create(string $name, string $description, string $color, string $banner): Faction
    {
        $faction =
            (new Faction())
            ->setName($name)
            ->setDescription($description)
            ->setColor($color)
            ->setBanner($banner)
        ;
        $this->entityManager->persist($faction);
        $this->entityManager->flush($faction);
        return $faction;
    }
}