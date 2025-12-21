<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\FlashNotification;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Instructor\RegisterInstructorRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\CourseService;
use App\Services\DashboardService;
use App\Services\RoleService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly UserService $userService,
        private readonly CourseService $courseService,
        private readonly RoleService $roleService
    ) {}

    /**
     * Render the admin dashboard view
     */
    public function dashboard(): View
    {
        return view('admin.index', [
            'id' => Auth::id(),
            ...$this->dashboardService->getStatistics(),
            'monthlySales' => $this->dashboardService->getMonthlySales(),
            'recentOrders' => $this->dashboardService->getRecentOrders(),
            ...$this->dashboardService->getPercentageChanges(),
        ]);
    }

    /**
     * Get chart data for AJAX requests
     */
    public function getChartData(): JsonResponse
    {
        return response()->json($this->dashboardService->getChartData());
    }

    /**
     * Render the login view
     */
    public function login(): View
    {
        return view('admin.login_dashboard');
    }

    /**
     * Log the admin out
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    /**
     * Render the admin profile edit view
     */
    public function admin_profile(): View
    {
        $adminProfile = $this->userService->getUserById(Auth::id());

        return view('admin.admin_profile', compact('adminProfile'));
    }

    /**
     * Update the admin profile
     */
    public function admin_update(ProfileUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            $admin = $this->userService->getUserById((int) $id);
            $this->userService->updateAdminProfile(
                $admin,
                $request->validated(),
                $request->file('photo')
            );

            return redirect()->back()->with(FlashNotification::success('Admin profile updated successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong. Please try again.'));
        }
    }

    /**
     * Render the change password view
     */
    public function change_password(): View
    {
        return view('admin.change_password');
    }

    public function update_password(ChangePasswordRequest $request, string $id): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->userService->verifyOldPassword($user, $request->old_password)) {
            return back()->with(FlashNotification::error('Old password does not match.'));
        }

        $this->userService->changePassword($user, $request->new_password);

        return back()->with(FlashNotification::success('Password changed successfully.'));
    }

    public function updateTheme(Request $request): JsonResponse
    {
        session(['theme' => $request->theme]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Get the admin theme preference
     */
    public function getThemePreference(): JsonResponse
    {
        $theme = session('theme', 'light');

        return response()->json(['theme' => $theme]);
    }

    public function all_instructors(): View
    {
        $instructors = $this->userService->getAllInstructors();

        return view('admin.backend.instructor.all_instructors', compact('instructors'));
    }

    /**
     * Update instructor status
     */
    public function update_instructor_status(string $id): JsonResponse
    {
        try {
            $instructor = $this->userService->getUserById((int) $id);
            $this->userService->toggleUserStatus($instructor);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function become_instructor(): View
    {
        return view('frontend.instructor.become_instructor');
    }

    /**
     * Register a new instructor
     */
    public function instructor_register(RegisterInstructorRequest $request): RedirectResponse
    {
        try {
            $this->userService->registerInstructor(
                $request->validated(),
                $request->file('photo')
            );

            return redirect()
                ->route('instructor.login')
                ->with(FlashNotification::success('Instructor registration successful. Please login to continue.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong. Please try again.'));
        }
    }

    public function all_courses(): View
    {
        $courses = $this->courseService->getAllCourses();

        return view('admin.backend.course.all_courses', compact('courses'));
    }

    /**
     * Toggle the course status
     */
    public function update_course_status(string $id): JsonResponse
    {
        try {
            $course = $this->courseService->findById((int) $id);
            $this->courseService->toggleCourseStatus($course);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function course_details(string $id): View
    {
        $course = $this->courseService->findById((int) $id);

        return view('admin.backend.course.course_details', compact('course'));
    }

    public function all_admins(): View
    {
        $admins = $this->userService->getAllAdmins();

        return view('admin.backend.pages.admin.all_admins', compact('admins'));
    }

    public function add_admins(): View
    {
        $roles = $this->roleService->getAllRoles();

        return view('admin.backend.pages.admin.add_admins', compact('roles'));
    }

    public function store_admin(StoreAdminRequest $request): RedirectResponse
    {
        try {
            $this->userService->createAdmin($request->validated(), $request->role);

            return redirect()
                ->route('admin.all_admins')
                ->with(FlashNotification::success('Admin created successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong. Please try again.'));
        }
    }

    public function edit_admin(string $id): View
    {
        $admin = $this->userService->getUserById((int) $id);
        $roles = $this->roleService->getAllRoles();

        return view('admin.backend.pages.admin.edit_admin', compact('admin', 'roles'));
    }

    public function update_admin(UpdateAdminRequest $request, string $id): RedirectResponse
    {
        try {
            $admin = $this->userService->getUserById((int) $id);
            $this->userService->updateAdmin($admin, $request->validated(), $request->role);

            return redirect()
                ->route('admin.all_admins')
                ->with(FlashNotification::success('Admin updated successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong. Please try again.'));
        }
    }

    public function delete_admin(string $id): RedirectResponse
    {
        try {
            $admin = $this->userService->getUserById((int) $id);
            $this->userService->deleteUser($admin);

            return redirect()->back()->with(FlashNotification::success('Admin deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops, something went wrong. Please try again.'));
        }
    }
}
