<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\ImageResizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload and optionally resize an image
     *
     * @param  string  $directory  Relative directory path (e.g., 'upload/course/images')
     * @param  int|null  $width  Resize width (null to skip resizing)
     * @param  int|null  $height  Resize height (null to skip resizing)
     * @return string The filename of the uploaded image
     */
    public function uploadImage(
        UploadedFile $file,
        string $directory,
        ?int $width = null,
        ?int $height = null
    ): string {
        $filename = $this->generateUniqueFilename($file);
        $fullPath = public_path("storage/{$directory}/{$filename}");

        // Ensure directory exists
        $this->ensureDirectoryExists("storage/{$directory}");

        if ($width && $height) {
            // Use ImageResizer for resizing
            ImageResizer::resize($file, $width, $height, $fullPath);
        } else {
            // Store without resizing
            $file->storeAs("public/{$directory}", $filename);
        }

        return $filename;
    }

    /**
     * Upload a video file
     *
     * @param  string  $directory  Relative directory path (e.g., 'upload/course/videos')
     * @return string The filename of the uploaded video
     */
    public function uploadVideo(UploadedFile $file, string $directory): string
    {
        $filename = $this->generateTimestampFilename($file);

        // Ensure directory exists
        $this->ensureDirectoryExists("storage/{$directory}");

        // Move to public storage
        $file->move(public_path("storage/{$directory}"), $filename);

        return $filename;
    }

    /**
     * Upload a file to storage
     *
     * @param  string  $directory  Relative directory path
     * @return string The filename of the uploaded file
     */
    public function uploadFile(UploadedFile $file, string $directory): string
    {
        $filename = $this->generateTimestampFilename($file);
        $file->storeAs("public/{$directory}", $filename);

        return $filename;
    }

    /**
     * Delete a file from storage or public path
     *
     * @param  string  $path  Full path or storage path to the file
     * @return bool True if deleted, false otherwise
     */
    public function deleteFile(string $path): bool
    {
        // Check if it's a storage path
        if (Storage::exists($path)) {
            return Storage::delete($path);
        }

        // Check if it's a public path
        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Delete a file from public storage directory
     *
     * @param  string  $directory  Relative directory path
     * @param  string  $filename  The filename to delete
     */
    public function deleteFromPublicStorage(string $directory, string $filename): bool
    {
        if (empty($filename)) {
            return false;
        }

        $storagePath = "public/{$directory}/{$filename}";

        if (Storage::exists($storagePath)) {
            return Storage::delete($storagePath);
        }

        $publicPath = public_path("storage/{$directory}/{$filename}");
        if (file_exists($publicPath)) {
            return unlink($publicPath);
        }

        return false;
    }

    /**
     * Generate a unique filename using hexdec(uniqid())
     */
    public function generateUniqueFilename(UploadedFile $file): string
    {
        return hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
    }

    /**
     * Generate a timestamp-based filename
     */
    public function generateTimestampFilename(UploadedFile $file): string
    {
        return date('YmdHis').'.'.$file->getClientOriginalExtension();
    }

    /**
     * Generate filename with original name prefixed by timestamp
     */
    public function generateTimestampPrefixedFilename(UploadedFile $file): string
    {
        return date('YmdHis').'_'.$file->getClientOriginalName();
    }

    /**
     * Ensure a directory exists
     */
    private function ensureDirectoryExists(string $directory): void
    {
        $fullPath = public_path($directory);

        if (! is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
    }

    /**
     * Check if a file exists in storage
     */
    public function fileExists(string $path): bool
    {
        return Storage::exists($path) || file_exists($path);
    }

    /**
     * Get the public URL for a stored file
     */
    public function getPublicUrl(string $directory, string $filename): string
    {
        return Storage::url("public/{$directory}/{$filename}");
    }
}

