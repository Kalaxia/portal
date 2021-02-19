<?php
namespace App\Utils;

class Slugger
{
    public function slugify(string $string): string
    {
        return preg_replace(
            '/[^a-z0-9]/', '-', strtolower(trim(strip_tags($string)))
        );
    }
}