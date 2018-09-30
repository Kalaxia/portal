<?php

namespace App\Model\Project;

interface EstimableInterface
{
    public function setEstimatedTime(int $estimatedTime): self;
    
    public function getEstimatedTime(): int;
    
    public function setSpentTime(int $spentTime): self;
    
    public function getSpentTime(): int;
}