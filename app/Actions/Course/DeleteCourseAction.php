<?php

declare(strict_types=1);

namespace App\Actions\Course;

use App\Models\Course;
use App\Services\FileUploadService;

class DeleteCourseAction
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    /**
     * Delete a course and its associated files
     */
    public function handle(Course $course): void
    {
        // Delete image
        $this->fileUploadService->deleteFromPublicStorage('upload/course/images', $course->image);

        // Delete video
        $this->fileUploadService->deleteFromPublicStorage('upload/course/videos', $course->video_link);

        // Delete goals
        $course->goals()->delete();

        // Delete the course
        $course->delete();
    }
}

