<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Sprint as SprintModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__sprints")
 */
class Sprint extends SprintModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $demoUrl;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $beginAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $endedAt;
}
