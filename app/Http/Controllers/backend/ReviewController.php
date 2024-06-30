<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Intervention\Image\Colors\Rgb\Channels\Red;

class ReviewController extends Controller
{
    public function review_store(string $id, string $course, Request $request) {

        $request->validate([
            'message' => 'required',
        ]);

        $course = Course::find($course);


        Review::create([
            'course_id' => $course->id,
            'user_id' => $id,
            'message' => $request->message,
            'rating' => $request->rate,
            'instructor_id' => $course->instructor_id
        ]);


        $notification = array(
            'message' => 'Review submitted successfully!',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }


    public function pending_reviews() {
        $reviews = Review::where('status', '0')->orderBy('id', 'DESC')->get();

        return view('admin.backend.reviews.pending_reviews', compact('reviews'));
    }

    public function update_review_status(string $id) {
        try {
            // Find the instructor
            $review = Review::find($id);
    
            // Toggle the instructor status
            if ($review->status == '1') {
                $review->status = '0';
            } else {
                $review->status = '1';
            }

            // Save the changes
            $review->save();
    
            // Return a success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => false , 'message' => $e->getMessage()], 500);
        }
    }


    public function active_reviews() {
        $reviews = Review::where('status', '1')->orderBy('id', 'DESC')->get();

        return view('admin.backend.reviews.active_reviews', compact('reviews'));
    }


    public function instructor_reviews(string $id) {

        $reviews = Review::where('instructor_id', $id)->where('status', '1')->orderBy('id', 'DESC')->get();

        return view('instructor.reviews.all_reviews', compact('reviews'));
    }
}
