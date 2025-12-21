<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\FlashNotification;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\User\UpdateUserProfileRequest;
use App\Services\HomeService;
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
        private readonly HomeService $homeService
    ) {}

    /**
     * Display the frontend index page
     */
    public function index(): View
    {
        return view('frontend.index', $this->homeService->getIndexPageData());
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
     */
    public function update_profile(UpdateUserProfileRequest $request, string $id): RedirectResponse
    {
        try {
            $user = $this->userService->getUserById((int) $id);
            $this->userService->updateProfile(
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

        if (! $this->userService->verifyOldPassword($user, $request->old_password)) {
            return redirect()->back()->with(FlashNotification::error('Old password does not match.'));
        }

        $this->userService->changePassword($user, $request->new_password);

        return redirect()->back()->with(FlashNotification::success('Password changed successfully.'));
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
