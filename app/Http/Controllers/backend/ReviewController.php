<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Services\ReviewService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewService $reviewService
    ) {}

    public function review_store(string $id, string $course, StoreReviewRequest $request): RedirectResponse
    {
        $this->reviewService->createReview(
            (int) $id,
            (int) $course,
            $request->validated()
        );

        return redirect()->back()->with(FlashNotification::success('Review submitted successfully!'));
    }

    public function pending_reviews(): View
    {
        $reviews = $this->reviewService->getPendingReviews();

        return view('admin.backend.reviews.pending_reviews', compact('reviews'));
    }

    public function update_review_status(string $id): JsonResponse
    {
        try {
            $review = $this->reviewService->findById((int) $id);
            $this->reviewService->toggleReviewStatus($review);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function active_reviews(): View
    {
        $reviews = $this->reviewService->getActiveReviews();

        return view('admin.backend.reviews.active_reviews', compact('reviews'));
    }

    public function instructor_reviews(string $id): View
    {
        $reviews = $this->reviewService->getInstructorReviews((int) $id);

        return view('instructor.reviews.all_reviews', compact('reviews'));
    }
}
