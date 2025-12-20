<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enums\AlertType;

class FlashNotification
{
    public static function success(string $message): array
    {
        return self::make($message, AlertType::SUCCESS);
    }

    public static function error(string $message): array
    {
        return self::make($message, AlertType::ERROR);
    }

    public static function warning(string $message): array
    {
        return self::make($message, AlertType::WARNING);
    }

    public static function info(string $message): array
    {
        return self::make($message, AlertType::INFO);
    }

    public static function make(string $message, AlertType $type): array
    {
        return [
            'message' => $message,
            'alert-type' => $type->value,
        ];
    }
}
