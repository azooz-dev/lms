<?php

declare(strict_types=1);

namespace App\Enums;

enum ReviewStatus: string
{
    case APPROVED = '1';
    case PENDING = '0';

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Approved',
            self::PENDING => 'Pending Review',
        };
    }
}

