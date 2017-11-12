<?php

namespace AppBundle\Model\Project;

class Evolution extends Feedback
{
    public function getType()
    {
        return self::TYPE_EVOLUTION;
    }
}