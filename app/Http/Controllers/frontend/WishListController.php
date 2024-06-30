<?php

namespace App\Http\Controllers\frontend;

use \Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wish_list;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WishListController extends Controller
{
    /**
     * Store course to wishlist
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_wishList(Request $request) {
        try {

            // Check if user is logged in
            if (Auth::check()) {
                // Check if course is already in wishlist
                $wishList = Wish_list::where('user_id', Auth::user()->id)
                    ->where('course_id', $request->id)
                    ->first();
    
                // If course is already in wishlist, remove it and return success message
                if ($wishList) {
                    $wishList->delete();
    
                    return response()->json(['success' => 'Course has been removed from your wishlist.'], 200);
                }
    
                // If course is not in wishlist, add it and return success message
                Wish_list::create([
                    'user_id' => Auth::user()->id,
                    'course_id' => $request->id,
                ]);
    
                return response()->json(['success' => 'Successfully added to your wishlist.'], 200);
            }
    
            // If user is not logged in, return error message
            return response()->json(['error' => 'At first login your account.'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function wishList_view() {
        return view('frontend.dashboard.wishlist.all_wishList');
    }

    public function all_wishList(string $id) {
        try {
            // Find the user by ID
            $user = User::find($id);
    
            // Retrieve the courses related to the user through the wishlist
            $courses = $user->wishlistCourses;

            foreach($courses as $course) {
                $course->image = Storage::url('upload/course/images/'. $course->image);
                $course->amount = round(($course->selling_price - $course->discount_price) / $course->selling_price * 100);
                $course->instructor = $course->instructor->name;
            }
    
            // Return the courses as a JSON response
            return response()->json(['courses' => $courses, 'courses_count' => $courses->count()], 200);
        } catch (Exception $e) {
            // Handle any exceptions, such as the user not being found
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete_wishlist(string $id, string $course) {
        try {
            // Find the wishlist item for the user and course
            $wishList = Wish_list::where('user_id', $id)
                ->where('course_id', $course)
                ->first();

            // If the wishlist item exists, delete it and return success message
            if ($wishList) {
                $wishList->delete();
                return response()->json(['success' => 'Successfully removed course from your wishlist.'], 200);
            }

            // If the wishlist item does not exist, return error message
            return response()->json(['error' => 'The course is not in your wishlist.'], 404);
        } catch (Exception $e) {
            // Handle any exceptions, such as the user or course not being found
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
