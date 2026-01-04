<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\InstructorDashboardService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly InstructorDashboardService $dashboardService
    ) {}

    /**
     * Show the instructor dashboard page.
     */
    public function dashboard(): View
    {
        $instructorId = Auth::id();

        return view('instructor.index', [
            'id' => $instructorId,
            ...$this->dashboardService->getStatistics($instructorId),
            'monthlySales' => $this->dashboardService->getMonthlySales($instructorId),
            'recentOrders' => $this->dashboardService->getRecentOrders($instructorId),
            'topCourses' => $this->dashboardService->getTopCourses($instructorId),
            'recentReviews' => $this->dashboardService->getRecentReviews($instructorId),
            'pendingQuestions' => $this->dashboardService->getPendingQuestions($instructorId),
            ...$this->dashboardService->getPercentageChanges($instructorId),
        ]);
    }

    /**
     * Get chart data for AJAX requests
     */
    public function getChartData(): JsonResponse
    {
        $instructorId = Auth::id();

        return response()->json($this->dashboardService->getChartData($instructorId));
    }


    /**
     * Log the instructor out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/instructor/login');
    }

    public function login(): View
    {
        return view('instructor.login_dashboard');
    }

    public function instructor_profile(): View
    {
        return view('instructor.instructor_profile');
    }

    /**
     * Update the instructor profile.
     */
    public function instructor_update(ProfileUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            $instructor = $this->userService->getUserById((int) $id);

            $this->userService->updateInstructorProfile(
                $instructor,
                $request->validated(),
                $request->file('photo')
            );

            $notification = [
                'message' => 'Instructor Profile Updated Successfully',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);
        } catch (Exception $e) {
            $notification = [
                'message' => 'An error occurred while updating the profile. Please try again later.',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification);
        }
    }

    public function change_password(): View
    {
        return view('instructor.change_password');
    }

    public function update_password(ChangePasswordRequest $request, string $id): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->userService->verifyOldPassword($user, $request->old_password)) {
            return back()->with('error', 'The old password does not match.');
        }

        try {
            $this->userService->changePassword($user, $request->new_password);

            $notification = [
                'message' => 'The Password changed successfully.',
                'alert-type' => 'success',
            ];

            return back()->with($notification);
        } catch (Exception $e) {
            $notification = [
                'message' => 'An error occurred while updating the password. Please try again later.',
                'alert-type' => 'error',
            ];

            return back()->with($notification);
        }
    }
}
