<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\ReviewSubmitted;
use App\Models\Review;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewService
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepository,
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function createReview(int $userId, int $courseId, array $data): Review
    {
        $course = $this->courseRepository->find($courseId);

        $review = $this->reviewRepository->create([
            'course_id' => $course->id,
            'user_id' => $userId,
            'message' => $data['message'],
            'rating' => $data['rate'],
            'instructor_id' => $course->instructor_id,
        ]);

        // Dispatch event to notify instructor
        ReviewSubmitted::dispatch($review);

        return $review;
    }

    public function getPendingReviews(): Collection
    {
        return $this->reviewRepository->getPendingReviews();
    }

    public function getActiveReviews(): Collection
    {
        return $this->reviewRepository->getActiveReviews();
    }

    public function getInstructorReviews(int $instructorId): Collection
    {
        return $this->reviewRepository->getByInstructorId($instructorId);
    }

    public function findById(int $id): ?Review
    {
        return $this->reviewRepository->find($id);
    }

    /**
     * Toggle review status (active/inactive)
     */
    public function toggleReviewStatus(Review $review): Review
    {
        $review->status = $review->status === '1' ? '0' : '1';
        $review->save();

        return $review;
    }
}
