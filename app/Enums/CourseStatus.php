<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseStatus: string
{
    case PUBLISHED = '1';
    case DRAFT = '0';

    public function label(): string
    {
        return match ($this) {
            self::PUBLISHED => 'Published',
            self::DRAFT => 'Draft',
        };
    }

    public function isPublished(): bool
    {
        return $this === self::PUBLISHED;
    }
}

