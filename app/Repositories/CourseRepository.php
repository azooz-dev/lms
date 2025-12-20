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

    public function createWithSlug(array $data): Course
    {
        $data['slug'] = $this->generateSlug($data['name']);

        return Course::create($data);
    }

    public function updateWithSlug(Course $course, array $data): Course
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $course->update($data);

        return $course;
    }

    private function generateSlug(string $name): string
    {
        return strtolower(str_replace(' ', '-', $name));
    }
}

