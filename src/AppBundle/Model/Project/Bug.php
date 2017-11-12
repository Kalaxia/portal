<?php

namespace AppBundle\Model\Project;

class Bug extends Feedback
{
    public function getType()
    {
        return self::TYPE_BUG;
    }
}