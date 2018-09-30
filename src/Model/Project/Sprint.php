<?php

namespace App\Model\Project;

abstract class Sprint
{
    /** @var int **/
    protected $id;
    /** @var string **/
    protected $demoUrl;
    /** @var \DateTime **/
    protected $beginAt;
    /** @var \DateTime **/
    protected $endedAt;
    
    public function setId(int $id): self
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setDemoUrl(string $demoUrl): self
    {
        $this->demoUrl = $demoUrl;
        
        return $this;
    }
    
    public function getDemoUrl(): ?string
    {
        return $this->demoUrl;
    }
    
    public function setBeginAt(\DateTime $beginAt): self
    {
        $this->beginAt = $beginAt;
        
        return $this;
    }
    
    public function getBeginAt(): \DateTime
    {
        return $this->beginAt;
    }
    
    public function setEndedAt(\DateTime $endedAt): self
    {
        $this->endedAt = $endedAt;
        
        return $this;
    }
    
    public function getEndedAt(): \DateTime
    {
        return $this->endedAt;
    }
}