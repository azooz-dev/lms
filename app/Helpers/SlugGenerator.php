<?php

declare(strict_types=1);

namespace App\Helpers;

class SlugGenerator
{
    /**
     * Generate a URL-friendly slug from a string.
     */
    public static function generate(string $value): string
    {
        return strtolower(str_replace(' ', '-', trim($value)));
    }
}
