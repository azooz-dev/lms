<?php

use App\Helpers\FlashNotification;

if (! function_exists('flash_success')) {
    function flash_success(string $message): array
    {
        return FlashNotification::success($message);
    }
}

if (! function_exists('flash_error')) {
    function flash_error(string $message): array
    {
        return FlashNotification::error($message);
    }
}

if (! function_exists('flash_warning')) {
    function flash_warning(string $message): array
    {
        return FlashNotification::warning($message);
    }
}

if (! function_exists('flash_info')) {
    function flash_info(string $message): array
    {
        return FlashNotification::info($message);
    }
}
