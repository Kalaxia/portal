<?php

namespace App\Utils;

class Parser
{
    public function parse(string $content): string
    {
        return str_replace("\n", '<br>', trim($content));
    }
}