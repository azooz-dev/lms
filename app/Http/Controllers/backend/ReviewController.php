<?php

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Models\Course;
use App\Models\Review;

class ReviewController extends Controller
{
    public function review_store(string $id, string $course, StoreReviewRequest $request)
    {
        $courseModel = Course::find($course);

        Review::create([
            'course_id' => $courseModel->id,
            'user_id' => $id,
            'message' => $request->message,
            'rating' => $request->rate,
            'instructor_id' => $courseModel->instructor_id,
        ]);

        return redirect()->back()->with(FlashNotification::success('Review submitted successfully!'));
    }

    public function pending_reviews()
    {
        $reviews = Review::where('status', '0')->orderBy('id', 'DESC')->get();

        return view('admin.backend.reviews.pending_reviews', compact('reviews'));
    }

    public function update_review_status(string $id)
    {
        try {
            $review = Review::find($id);

            // Toggle the review status
            $review->status = $review->status == '1' ? '0' : '1';
            $review->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function active_reviews()
    {
        $reviews = Review::where('status', '1')->orderBy('id', 'DESC')->get();

        return view('admin.backend.reviews.active_reviews', compact('reviews'));
    }

    public function instructor_reviews(string $id)
    {
        $reviews = Review::where('instructor_id', $id)->where('status', '1')->orderBy('id', 'DESC')->get();

        return view('instructor.reviews.all_reviews', compact('reviews'));
    }
}
