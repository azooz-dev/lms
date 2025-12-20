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
    /**
     * Render the admin dashboard view
     */
    public function dashboard(): View
    {
        $id = Auth::user()->id;

        // Get dashboard statistics
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->sum('orders.course_price');
        $totalCustomers = \App\Models\User::where('role', 'user')->count();
        $totalCourses = \App\Models\Course::count();

        // Get monthly sales data for chart
        $monthlySales = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereYear('orders.created_at', date('Y'))
            ->selectRaw('MONTH(orders.created_at) as month, SUM(orders.course_price) as total_sales, COUNT(*) as order_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get recent orders
        $recentOrders = \App\Models\Order::with(['course', 'user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Calculate percentage changes (simplified - you can make this more sophisticated)
        $lastWeekOrders = \App\Models\Order::whereBetween('created_at', [now()->subWeek(), now()])->count();
        $previousWeekOrders = \App\Models\Order::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();
        $orderChange = $previousWeekOrders > 0 ? (($lastWeekOrders - $previousWeekOrders) / $previousWeekOrders) * 100 : 0;

        // Get additional statistics
        $totalInstructors = \App\Models\User::where('role', 'instructor')->count();
        $pendingOrders = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'pending')
            ->count();
        $completedOrders = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->count();
        $totalReviews = \App\Models\Review::count();
        $pendingReviews = \App\Models\Review::where('status', '0')->count();

        $lastWeekRevenue = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereBetween('orders.created_at', [now()->subWeek(), now()])
            ->sum('orders.course_price');
        $previousWeekRevenue = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereBetween('orders.created_at', [now()->subWeeks(2), now()->subWeek()])
            ->sum('orders.course_price');
        $revenueChange = $previousWeekRevenue > 0 ? (($lastWeekRevenue - $previousWeekRevenue) / $previousWeekRevenue) * 100 : 0;

        $lastWeekCustomers = \App\Models\User::where('role', 'user')
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->count();
        $previousWeekCustomers = \App\Models\User::where('role', 'user')
            ->whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])
            ->count();
        $customerChange = $previousWeekCustomers > 0 ? (($lastWeekCustomers - $previousWeekCustomers) / $previousWeekCustomers) * 100 : 0;

        return view('admin.index', compact(
            'id',
            'totalOrders',
            'totalRevenue',
            'totalCustomers',
            'totalCourses',
            'monthlySales',
            'recentOrders',
            'orderChange',
            'revenueChange',
            'customerChange',
            'totalInstructors',
            'pendingOrders',
            'completedOrders',
            'totalReviews',
            'pendingReviews'
        ));
    }

    /**
     * Get chart data for AJAX requests
     */
    public function getChartData(): JsonResponse
    {
        // Get monthly sales data for chart
        $monthlySales = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereYear('orders.created_at', date('Y'))
            ->selectRaw('MONTH(orders.created_at) as month, SUM(orders.course_price) as total_sales, COUNT(*) as order_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get daily data for the current month
        $dailySales = \App\Models\Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereMonth('orders.created_at', date('m'))
            ->whereYear('orders.created_at', date('Y'))
            ->selectRaw('DATE(orders.created_at) as date, SUM(orders.course_price) as total_sales, COUNT(*) as order_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'monthlySales' => $monthlySales,
            'dailySales' => $dailySales,
        ]);
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
