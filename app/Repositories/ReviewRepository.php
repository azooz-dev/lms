<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Support\Collection;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Review);
    }

    public function getPendingCount(): int
    {
        return Review::where('status', '0')->count();
    }

    public function getPendingReviews(): Collection
    {
        return Review::where('status', '0')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getActiveReviews(): Collection
    {
        return Review::where('status', '1')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getByInstructorId(int $instructorId): Collection
    {
        return Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function countByInstructor(int $instructorId): int
    {
        return Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->count();
    }

    public function getAverageRatingByInstructor(int $instructorId): float
    {
        $avgRating = Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->avg('rating');

        return round((float) ($avgRating ?? 0), 1);
    }
}
