<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\Review;
use App\Models\User;
use App\Models\Wish_list;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    
    /**
     * Display the frontend index page
     *
     * This function fetches the 6 most recent categories, the 6 most recent courses
     * with a status of 1, the 6 most recent courses with a featured status of 1 and a
     * status of 1, and the 6 most recent posts. It also fetches all wish lists and
     * reviews with a status of 1. These are then passed to the view 'frontend.index'.
     *
     * @return View
     */
    public function index(): View
    {
        // Get the 6 most recent categories
        $categories = Category::latest()->get();

        // Get the 6 most recent courses with a status of 1
        $courses = Course::where('status', '1')->latest()->limit(6)->get();

        // Get the 6 most recent courses with a featured status of 1 and a status of 1
        $coursesFeatured = Course::where('featured', '1')
            ->where('status', '1')
            ->latest()
            ->limit(6)
            ->get();

        // Get all wish lists
        $wishList = Wish_list::all();

        // Get the 6 most recent posts
        $posts = Post::latest()->limit(6)->get();

        // Get all reviews with a status of 1
        $reviews = Review::where('status', '1')->get();

        // Pass the variables to the view
        return view(
            'frontend.index',
            [
                'categories' => $categories,
                'courses' => $courses,
                'wishLists' => $wishList,
                'posts' => $posts,
                'coursesFeatured' => $coursesFeatured,
                'reviews' => $reviews
            ]
        );
    }

    /**
     * Display the dashboard page
     *
     * @return View
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        return view('frontend.dashboard.index', compact('user'));
    }

    /**
     * Display the profile page of a user
     *
     * @param string $id User ID
     * @return View
     */
    public function profile(string $id): View
    {
        $user = User::find($id);
        return view('frontend.dashboard.profile', compact('user'));
    }



    /**
     * Logout the user
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show user settings view
     *
     * @param string $id
     * @return View
     */
    public function user_settings(string $id): View
    {
        $user = User::find($id);

        return view('frontend.dashboard.settings', compact('user'));
    }


    /**
     * Update user profile
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update_profile(Request $request, string $id): RedirectResponse
    {
        $user = User::find($id);
        $dataValidated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::user()->id,
            'photo' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('photo')) {
                // delete existing photo if it exists
                if (!empty($user->photo) && Storage::exists('public/upload/users_images/' . $user->photo)) {
                    Storage::delete('public/upload/users_images/' . $user->photo);
                }
                $dataValidated['photo'] = date('YmdHis') . '_' . $request->file('photo')->getClientOriginalName();
                // store the new photo
                $request->file('photo')->storeAs('public/upload/users_images', $dataValidated['photo']);
            } else {
                // remove photo attribute if there is no new photo
                unset($dataValidated['photo']);
            }

            $user->update($dataValidated);

            $notification = [
                'message' => 'Profile Updated Successfully',
                'alert-type' => 'success',
            ];
            return redirect()->back()->with($notification);
        } catch (Exception $e) {
            $notification = [
                'message' => 'Something went wrong! Please try again.',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }
    }


    public function change_password(ChangePasswordRequest $request, string $id)
    {
        // if the old password is correct and matching with the current password
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            $notification = array(
                'message' => 'The old password does not match.',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }

        try {
            // if the old password is correct, update the password
            User::whereId($id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            $notification = array(
                'message' => "The Password changed successfully.",
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Something went wrong! Please try again.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function change_email(ChangeEmailRequest $request, string $id)
    {


        try {
            // The request validated automatically, so you can proceed with updating the email
            $user = User::find($id);
            $user->email = $request->new_email;
            $user->save();

            // Optionally, send a confirmation email to the new email address
            // Mail::to($user->new_email)->send(new EmailChangeConfirmation($user));

            $notification = array(
                'message' => "The Email changed successfully.",
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Something went wrong! Please try again.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }



}
