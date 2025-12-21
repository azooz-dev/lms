<?php

namespace App\Http\Controllers;

use App\Actions\User\ChangePasswordAction;
use App\Actions\User\UpdateUserProfileAction;
use App\Helpers\FlashNotification;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\User\UpdateUserProfileRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\Review;
use App\Models\User;
use App\Models\Wish_list;
use App\Services\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UpdateUserProfileAction $updateUserProfileAction,
        private readonly ChangePasswordAction $changePasswordAction
    ) {}

    /**
     * Display the frontend index page
     *
     * This function fetches the 6 most recent categories, the 6 most recent courses
     * with a status of 1, the 6 most recent courses with a featured status of 1 and a
     * status of 1, and the 6 most recent posts. It also fetches all wish lists and
     * reviews with a status of 1. These are then passed to the view 'frontend.index'.
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
                'reviews' => $reviews,
            ]
        );
    }

    /**
     * Display the dashboard page
     */
    public function dashboard(): View
    {
        $user = Auth::user();

        return view('frontend.dashboard.index', compact('user'));
    }

    /**
     * Display the profile page of a user
     *
     * @param  string  $id  User ID
     */
    public function profile(string $id): View
    {
        $user = $this->userService->getUserById((int) $id);

        return view('frontend.dashboard.profile', compact('user'));
    }

    /**
     * Logout the user
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
     */
    public function user_settings(string $id): View
    {
        $user = $this->userService->getUserById((int) $id);

        return view('frontend.dashboard.settings', compact('user'));
    }

    /**
     * Update user profile
     *
     * @param  UpdateUserProfileRequest  $request  The validated request object
     */
    public function update_profile(UpdateUserProfileRequest $request, string $id): RedirectResponse
    {
        try {
            $user = $this->userService->getUserById((int) $id);
            $this->updateUserProfileAction->handle(
                $user,
                $request->validated(),
                $request->file('photo')
            );

            return redirect()->back()->with(FlashNotification::success('Profile Updated Successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong! Please try again.'));
        }
    }

    public function change_password(ChangePasswordRequest $request, string $id): RedirectResponse
    {
        $user = Auth::user();

        $result = $this->changePasswordAction->handle(
            $user,
            $request->old_password,
            $request->new_password
        );

        if ($result['success']) {
            return redirect()->back()->with(FlashNotification::success($result['message']));
        }

        return redirect()->back()->with(FlashNotification::error($result['message']));
    }

    public function change_email(ChangeEmailRequest $request, string $id): RedirectResponse
    {
        try {
            $user = $this->userService->getUserById((int) $id);
            $this->userService->changeEmail($user, $request->new_email);

            return redirect()->back()->with(FlashNotification::success('The Email changed successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong! Please try again.'));
        }
    }
}
