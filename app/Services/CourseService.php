<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Course\CreateCourseAction;
use App\Actions\Course\DeleteCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Models\Course;
use App\Models\Course_Lecture;
use App\Models\Course_Section;
use Illuminate\Http\UploadedFile;

class CourseService
{
    public function __construct(
        private readonly FileUploadService $fileUploadService,
        private readonly CreateCourseAction $createCourseAction,
        private readonly UpdateCourseAction $updateCourseAction,
        private readonly DeleteCourseAction $deleteCourseAction
    ) {}

    /**
     * Create a new course with image, video, and goals
     */
    public function createCourse(array $data, UploadedFile $image, UploadedFile $video, array $goals, int $instructorId): Course
    {
        return $this->createCourseAction->handle($data, $image, $video, $goals, $instructorId);
    }

    /**
     * Update an existing course
     */
    public function updateCourse(Course $course, array $data, ?UploadedFile $image = null): Course
    {
        return $this->updateCourseAction->handle($course, $data, $image);
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
        $this->deleteCourseAction->handle($course);
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
}
