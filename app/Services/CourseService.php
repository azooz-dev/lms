<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\Course_Lecture;
use App\Models\Course_Section;
use Illuminate\Http\UploadedFile;

class CourseService
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    /**
     * Create a new course with image, video, and goals
     */
    public function createCourse(array $data, UploadedFile $image, UploadedFile $video, array $goals, int $instructorId): Course
    {
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
     * Update an existing course
     */
    public function updateCourse(Course $course, array $data, ?UploadedFile $image = null): Course
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

    /**
     * Update course video
     */
    public function updateCourseVideo(Course $course, UploadedFile $video): void
    {
        // Delete old video
        $this->fileUploadService->deleteFromPublicStorage('upload/course/videos', $course->video_link);

        // Upload new video
        $videoName = $this->fileUploadService->uploadFile($video, 'upload/course/videos');

        $course->update(['video_link' => $videoName]);
    }

    /**
     * Update course goals
     */
    public function updateCourseGoals(Course $course, array $goals): bool
    {
        // Filter empty goals
        $filteredGoals = array_filter($goals, function ($value) {
            return ! is_null($value) && $value !== '';
        });

        if (empty($filteredGoals)) {
            return false;
        }

        // Delete existing goals
        $course->goals()->delete();

        // Create new goals
        foreach ($filteredGoals as $goalText) {
            $course->goals()->create([
                'goal' => $goalText,
            ]);
        }

        return true;
    }

    /**
     * Delete a course and its associated files
     */
    public function deleteCourse(Course $course): void
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

    /**
     * Create a section for a course
     */
    public function createSection(Course $course, string $title): Course_Section
    {
        return $course->sections()->create([
            'section_title' => $title,
        ]);
    }

    /**
     * Delete a section and its lectures
     */
    public function deleteSection(Course_Section $section): void
    {
        $section->lectures()->delete();
        $section->delete();
    }

    /**
     * Create a lecture for a section
     */
    public function createLecture(Course_Section $section, array $data): Course_Lecture
    {
        return $section->lectures()->create([
            'course_id' => $section->course_id,
            'lecture_title' => $data['lecture_title'],
            'content' => $data['content'] ?? null,
            'url' => $data['url'] ?? null,
        ]);
    }

    /**
     * Update a lecture
     */
    public function updateLecture(Course_Lecture $lecture, array $data): Course_Lecture
    {
        $lecture->update([
            'lecture_title' => $data['lecture_title'],
            'content' => $data['content'] ?? null,
            'url' => $data['url'] ?? null,
        ]);

        return $lecture;
    }

    /**
     * Delete a lecture
     */
    public function deleteLecture(Course_Lecture $lecture): void
    {
        $lecture->delete();
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
