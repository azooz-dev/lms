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

    public function toggleStatus(Review $review): Review
    {
        $review->status = $review->status === '1' ? '0' : '1';
        $review->save();

        return $review;
    }
}

