<?php

namespace App\Http\Controllers;

use App\Actions\User\ChangePasswordAction;
use App\Actions\User\CreateAdminAction;
use App\Actions\User\RegisterInstructorAction;
use App\Actions\User\UpdateUserProfileAction;
use App\Helpers\FlashNotification;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Instructor\RegisterInstructorRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Course;
use App\Services\DashboardService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly UserService $userService,
        private readonly RegisterInstructorAction $registerInstructorAction,
        private readonly CreateAdminAction $createAdminAction,
        private readonly UpdateUserProfileAction $updateUserProfileAction,
        private readonly ChangePasswordAction $changePasswordAction
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
            $this->updateUserProfileAction->handleAdmin(
                $admin,
                $request->validated(),
                $request->file('photo')
            );

            return redirect()->back()->with(FlashNotification::success('Admin profile updated successfully.'));
        } catch (\Exception $e) {
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

        $result = $this->changePasswordAction->handle(
            $user,
            $request->old_password,
            $request->new_password
        );

        if ($result['success']) {
            return back()->with(FlashNotification::success($result['message']));
        }

        return back()->with(FlashNotification::error($result['message']));
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
        $theme = session('theme', 'light'); // Default to 'light' if no theme is set

        return response()->json(['theme' => $theme]);
    }

    public function all_instructors(): View
    {
        $instructors = $this->userService->getAllInstructors();

        return view('admin.backend.instructor.all_instructors', compact('instructors'));
    }

    /**
     * Update instructor status
     *
     * @param  string  $id  Instructor ID
     */
    public function update_instructor_status(string $id): JsonResponse
    {
        try {
            $instructor = $this->userService->getUserById((int) $id);
            $this->userService->toggleUserStatus($instructor);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function become_instructor(): View
    {
        return view('frontend.instructor.become_instructor');
    }

    /**
     * Register a new instructor
     *
     * @param  RegisterInstructorRequest  $request  The validated request object
     */
    public function instructor_register(RegisterInstructorRequest $request): RedirectResponse
    {
        try {
            $this->registerInstructorAction->handle(
                $request->validated(),
                $request->file('photo')
            );

            return redirect()
                ->route('instructor.login')
                ->with(FlashNotification::success('Instructor registration successful. Please login to continue.'));
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong. Please try again.'));
        }
    }

    public function all_courses()
    {
        $courses = Course::latest()->get();

        return view('admin.backend.course.all_courses', compact('courses'));
    }

    /**
     * Toggle the course status
     *
     * @param  string  $id  Course ID
     */
    public function update_course_status(string $id): JsonResponse
    {
        try {
            // Find the course
            $course = Course::findOrFail($id);

            // Toggle the course status
            if ($course->status == '1') {
                $course->status = '0';
            } else {
                $course->status = '1';
            }

            // Save the changes
            $course->save();

            // Return a success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function course_details(string $id)
    {
        $course = Course::find($id);

        return view('admin.backend.course.course_details', compact('course'));
    }

    public function all_admins(): View
    {
        $admins = $this->userService->getAllAdmins();

        return view('admin.backend.pages.admin.all_admins', compact('admins'));
    }

    public function add_admins(): View
    {
        $roles = Role::all();

        return view('admin.backend.pages.admin.add_admins', compact('roles'));
    }

    public function store_admin(StoreAdminRequest $request): RedirectResponse
    {
        try {
            $this->createAdminAction->handle($request->validated(), $request->role);

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
        $roles = Role::all();

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
