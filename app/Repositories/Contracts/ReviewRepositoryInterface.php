<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Review;
use Illuminate\Support\Collection;

interface ReviewRepositoryInterface extends RepositoryInterface
{
    public function getPendingCount(): int;

    public function getPendingReviews(): Collection;

    public function getActiveReviews(): Collection;

    public function getByInstructorId(int $instructorId): Collection;

    public function toggleStatus(Review $review): Review;
}

