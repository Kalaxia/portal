<?php

namespace App\Manager\Game;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Game\Faction;
use App\Entity\Game\FactionColors;

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

    protected function createColorSet(array $data): FactionColors
    {
        return (new FactionColors())
            ->setBlack($data['color-black'] ?? "#00000000")
            ->setGrey($data['color-grey'] ?? "#00000000")
            ->setWhite($data['color-white'] ?? "#00000000")
            ->setMain($data['color-main'] ?? "#00000000")
            ->setMainLight($data['color-mainLight'] ?? "#00000000")
            ->setMainLighter($data['color-mainLighter'] ?? "#00000000")
        ;
    }

    public function create(array $data): Faction
    {
        $faction =
            (new Faction())
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setColors($this->createColorSet($data))
            ->setBanner($data['banner'])
        ;
        $this->entityManager->persist($faction);
        $this->entityManager->flush($faction);
        return $faction;
    }
}
