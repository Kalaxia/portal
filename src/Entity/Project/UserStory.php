<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\UserStory as UserStoryModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__user_stories")
 */
class UserStory extends UserStoryModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=15)
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\Column(type="integer")
     */
    protected $value;
    /**
     * @ORM\Column(type="integer")
     */
    protected $estimatedTime;
    /**
     * @ORM\Column(type="integer")
     */
    protected $spentTime;
    /**
     * @ORM\ManyToOne(targetEntity="Sprint")
     */
    protected $sprint;
    /**
     * @ORM\ManyToOne(targetEntity="Epic")
     */
    protected $epic;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;
}