<?php

namespace App\Model\Project;

abstract class Epic implements EstimableInterface
{
    /** @var string **/
    protected $id;
    /** @var string **/
    protected $title;
    /** @var int **/
    protected $estimatedTime;
    /** @var int **/
    protected $spentTime;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
    
    public function setId(string $id): self
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): string
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setEstimatedTime(int $estimatedTime): EstimableInterface
    {
        $this->estimatedTime = $estimatedTime;
        
        return $this;
    }
    
    public function getEstimatedTime(): int
    {
        return $this->getEstimatedTime();
    }
    
    public function setSpentTime(int $spentTime): EstimableInterface
    {
        $this->spentTime = $spentTime;
    }
    
    public function getSpentTime(): int
    {
        return $this->spentTime;
    }
    
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}