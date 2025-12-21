<?php

declare(strict_types=1);

namespace App\Actions\Course;

use App\Models\Course;
use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;

class CreateCourseAction
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    /**
     * Create a new course with image, video, and goals
     */
    public function handle(
        array $data,
        UploadedFile $image,
        UploadedFile $video,
        array $goals,
        int $instructorId
    ): Course {
        // Upload image with resizing
        $imageName = $this->fileUploadService->uploadImage(
            $image,
            'upload/course/images',
            370,
            246
        );

        // Upload video
        $videoName = $this->fileUploadService->uploadVideo($video, 'upload/course/videos');

        // Create the course
        $course = Course::create([
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'instructor_id' => $instructorId,
            'image' => $imageName,
            'name' => $data['name'],
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['name']),
            'description' => $data['description'] ?? null,
            'video_link' => $videoName,
            'course_level' => $data['level'] ?? null,
            'duration' => $data['duration'] ?? null,
            'resources' => $data['resources'] ?? null,
            'selling_price' => $data['selling_price'] ?? null,
            'discount_price' => $data['discount_price'] ?? null,
            'certificate' => $data['certificate'] ?? null,
            'prerequisites' => $data['prerequisites'] ?? null,
            'best_seller' => $data['best_seller'] ?? null,
            'featured' => $data['featured'] ?? null,
            'highest_rated' => $data['highest_rated'] ?? null,
            'status' => '1',
        ]);

        // Create course goals
        $this->createGoals($course, $goals);

        return $course;
    }

    /**
     * Create goals for a course
     */
    private function createGoals(Course $course, array $goals): void
    {
        foreach ($goals as $goalText) {
            $course->goals()->create([
                'goal' => $goalText,
            ]);
        }
    }

    /**
     * Generate a URL-friendly slug from course name
     */
    private function generateSlug(string $name): string
    {
        return strtolower(str_replace(' ', '-', $name));
    }
}

