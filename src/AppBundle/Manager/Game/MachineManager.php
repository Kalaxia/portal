<?php

namespace AppBundle\Manager\Game;

use AppBundle\Entity\Game\Machine;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Utils\Slugger;

class MachineManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    //** @var Slugger **/
    protected $slugger;
    
    public function __construct(EntityManagerInterface $em, Slugger $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }
    
    public function get(int $id): ?Machine
    {
        return $this->em->getRepository(Machine::class)->find($id);
    }
    
    public function getAll()
    {
        return $this->em->getRepository(Machine::class)->findAll();
    }
    
    public function create(string $name, string $host, string $publicKey, bool $isLocal): Machine
    {
        $machine =
            (new Machine())
            ->setName($name)
            ->setSlug($this->slugger->slugify($name))
            ->setHost($host)
            ->setPublicKey($publicKey)
            ->setIsLocal($isLocal)
        ;
        $this->em->persist($machine);
        $this->em->flush($machine);
        return $machine;
    }
}