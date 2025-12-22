<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Collection;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Course);
    }

    public function getByInstructorId(int $instructorId): Collection
    {
        return Course::where('instructor_id', $instructorId)->get();
    }

    public function getAllLatest(): Collection
    {
        return Course::latest()->get();
    }

    public function getActiveCoursesLatest(int $limit): Collection
    {
        return Course::where('status', '1')->latest()->limit($limit)->get();
    }

    public function getFeaturedCourses(int $limit): Collection
    {
        return Course::where('featured', '1')
            ->where('status', '1')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function findByIdAndSlug(int $id, string $slug): ?Course
    {
        return Course::where('id', $id)->where('slug', $slug)->first();
    }
}
