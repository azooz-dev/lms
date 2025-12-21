<?php

declare(strict_types=1);

namespace App\Actions\Course;

use App\Models\Course;
use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;

class UpdateCourseAction
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    /**
     * Update an existing course
     */
    public function handle(Course $course, array $data, ?UploadedFile $image = null): Course
    {
        $updateData = $data;

        if ($image) {
            // Upload new image
            $imageName = $this->fileUploadService->uploadImage(
                $image,
                'upload/course/images',
                370,
                246
            );

            // Delete old image
            $this->fileUploadService->deleteFromPublicStorage('upload/course/images', $course->image);

            $updateData['image'] = $imageName;
        } else {
            unset($updateData['image']);
        }

        // Handle checkboxes
        $updateData['best_seller'] = isset($updateData['best_seller']) ? '1' : '0';
        $updateData['featured'] = isset($updateData['featured']) ? '1' : '0';
        $updateData['highest_rated'] = isset($updateData['highest_rated']) ? '1' : '0';

        $course->update($updateData);

        return $course;
    }
}
