<?php

namespace App\Model\Project;

class Version
{
    /** @var string **/
    protected $id;
    /** @var \DateTime **/
    protected $plannedAt;
    /** @var \DateTime **/
    protected $releasedAt;
    
    public function setId(string $id): self
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function setPlannedAt(\DateTime $plannedAt): self
    {
        $this->plannedAt = $plannedAt;
        
        return $this;
    }
    
    public function getPlannedAt(): \DateTime
    {
        return $this->plannedAt;
    }
    
    public function setReleasedAt(\DateTime $releasedAt): self
    {
        $this->releasedAt = $releasedAt;
        
        return $this;
    }
    
    public function getReleasedAt(): \DateTime
    {
        return $this->releasedAt;
    }
}