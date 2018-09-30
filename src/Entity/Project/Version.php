<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Version as VersionModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__versions")
 */
class Version extends VersionModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=15)
     */
    protected $id;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $plannedAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $releasedAt;
}