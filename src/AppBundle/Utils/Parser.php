<?php

namespace AppBundle\Utils;

class Parser
{
    /**
     * @param string $content
     * @return string
     */
    public function parse($content)
    {
        return str_replace("\n", '<br>', trim($content));
    }
}