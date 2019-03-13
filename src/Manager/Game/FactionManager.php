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

    public function createColorSet(array $colors): FactionColors
    {
        $colorSet =
            (new FactionColors())
            ->setBlack($colors['black'])
            ->setGrey($colors['grey'])
            ->setWhite($colors['white'])
            ->setMain($colors['main'])
            ->setMainLight($colors['mainLight'])
            ->setMainLighter($colors['mainLighter'])
        ;
        $this->entityManager->persist($colorSet);
        $this->entityManager->flush($colorSet);
        return $colorSet;
    }

    public function create(string $name, string $description, FactionColors $color, string $banner): Faction
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
