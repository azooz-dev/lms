<?php

namespace App\Http\Controllers;

use App\Helpers\FlashNotification;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Instructor\RegisterInstructorRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Course;
use App\Models\User;
use App\Services\DashboardService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
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
        $id = Auth::user()->id;
        $adminProfile = User::find($id);

        return view('admin.admin_profile', compact('adminProfile'));
    }

    /**
     * Update the admin profile
     */
    public function admin_update(ProfileUpdateRequest $request, string $id): RedirectResponse
    {
        $admin = User::find($id);
        $input = $request->validated();

        try {
            if ($request->hasFile('photo')) {
                if ($admin->photo && Storage::exists("public/upload/admin_images/$admin->photo")) {
                    Storage::delete("public/upload/admin_images/$admin->photo");
                }
                $input['photo'] = date('YmdHis').'_'.$request->file('photo')->getClientOriginalName();
                $request->file('photo')->storeAs('public/upload/admin_images', $input['photo']);
            } else {
                unset($input['photo']);
            }

            $admin->update($input);

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
        if (! Hash::check($request->old_password, Auth::user()->password)) {
            return back()->with('error', 'The old password does not match.');
        }

        try {
            User::whereId($id)->update(['password' => Hash::make($request->new_password)]);

            return back()->with(FlashNotification::success('The Password changed successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Something went wrong! Please try again.'));
        }
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
        $instructors = User::where('role', 'instructor')->latest()->get();

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
            // Find the instructor
            $instructor = User::find($id);

            // Toggle the instructor status
            if ($instructor->status == '1') {
                $instructor->status = '0';
            } else {
                $instructor->status = '1';
            }

            // Save the changes
            $instructor->save();

            // Return a success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function become_instructor()
    {
        return view('frontend.instructor.become_instructor');
    }

    /**
     * Register a new instructor
     *
     * @param  RegisterInstructorRequest  $request  The validated request object
     * @return \Illuminate\Http\RedirectResponse
     */
    public function instructor_register(RegisterInstructorRequest $request)
    {
        $validatedData = $request->validated();

        // Save the instructor's photo if there is one
        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoName = date('YmdHis').'_'.$request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('public/upload/instructor_images', $photoName);
        }

        try {
            User::create([
                'name' => $validatedData['name'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'photo' => $photoName,
                'address' => $validatedData['address'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'instructor',
                'status' => '0',
                'bio' => $validatedData['bio'] ?? null,
            ]);

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

    public function all_admins()
    {
        $admins = User::where('role', 'admin')->get();

        return view('admin.backend.pages.admin.all_admins', compact('admins'));
    }

    public function add_admins()
    {
        $roles = Role::all();

        return view('admin.backend.pages.admin.add_admins', compact('roles'));
    }

    public function store_admin(StoreAdminRequest $request)
    {
        try {
            $data = $request->validated();
            $data['role'] = 'admin';
            $data['password'] = Hash::make($data['password']);

            $admin = User::create($data);
            $admin->assignRole($request->role);

            return redirect()
                ->route('admin.all_admins')
                ->with(FlashNotification::success('Admin created successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong. Please try again.'));
        }
    }

    public function edit_admin(string $id)
    {

        $admin = User::find($id);
        $roles = Role::all();

        return view('admin.backend.pages.admin.edit_admin', compact('admin', 'roles'));
    }

    public function update_admin(UpdateAdminRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $data['role'] = 'admin';

            $admin = User::find($id);
            $admin->update($data);
            $admin->syncRoles($request->role);

            return redirect()
                ->route('admin.all_admins')
                ->with(FlashNotification::success('Admin updated successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Something went wrong. Please try again.'));
        }
    }

    public function delete_admin(string $id)
    {
        try {
            User::find($id)->delete();

            return redirect()->back()->with(FlashNotification::success('Admin deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops, something went wrong. Please try again.'));
        }
    }
}
