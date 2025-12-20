<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;

class ImageResizer
{
    public static function resize(
        $image,
        int $width,
        int $height,
        string $destinationPath
    ): void {
        $tempInputPath = $image->getRealPath();
        $resizeCommand = sprintf(
            'magick convert %s -resize %dx%d^ -gravity center -extent %dx%d %s',
            escapeshellarg($tempInputPath),
            $width,
            $height,
            $width,
            $height,
            escapeshellarg($destinationPath)
        );

        exec($resizeCommand, $output, $returnVar);

        if ($returnVar !== 0 || !file_exists($destinationPath)) {
            throw new Exception('Unable to resize image');
        }
    }
}

