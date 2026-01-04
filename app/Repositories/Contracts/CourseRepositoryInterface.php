<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Support\Collection;

interface CourseRepositoryInterface extends RepositoryInterface
{
    public function getByInstructorId(int $instructorId): Collection;

    public function getAllLatest(): Collection;

    public function getActiveCoursesLatest(int $limit): Collection;

    public function getFeaturedCourses(int $limit): Collection;

    public function findByIdAndSlug(int $id, string $slug): ?Course;

    public function countByInstructor(int $instructorId): int;
}
