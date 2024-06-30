<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
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
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{

    /**
     * Render the admin dashboard view
     *
     * @return \Illuminate\View\View
     */
    public function dashboard(): View
    {
        $id = Auth::user()->id;
        return view('admin.index', compact('id'));
    }

    /**
     * Render the login view
     *
     * @return \Illuminate\View\View
     */
    public function login(): View
    {
        return view('admin.login_dashboard');
    }

    /**
     * Log the admin out
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
     *
     * @return \Illuminate\View\View
     */
    public function admin_profile(): View
    {
        $id = Auth::user()->id;
        $adminProfile = User::find($id);
        return view('admin.admin_profile', compact('adminProfile'));
    }

    /**
     * Update the admin profile
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
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
                $input['photo'] = date('YmdHis') . '_' . $request->file('photo')->getClientOriginalName();
                $request->file('photo')->storeAs('public/upload/admin_images', $input['photo']);
            } else {
                unset($input['photo']);
            }

            $admin->update($input);

            $notification = [
                'message' => 'Admin profile updated successfully.',
                'alert-type' => 'success'
            ];

            return redirect()->back()->with($notification);

        } catch (\Exception $e) {
            $notification = [
                'message' => 'Something went wrong. Please try again.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }



    /**
     * Render the change password view
     *
     * @return \Illuminate\View\View
     */
    public function change_password(): View
    {
        return view('admin.change_password');
    }

    public function update_password(ChangePasswordRequest $request, string $id): RedirectResponse
    {
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->with('error', 'The old password does not match.');
        }

        try {
            User::whereId($id)->update(['password' => Hash::make($request->new_password)]);

            $notification = array(
                'message' => "The Password changed successfully.",
                'alert-type' => 'success',
            );
            return back()->with($notification);
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Something went wrong! Please try again.',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }
    }



    public function updateTheme(Request $request): JsonResponse
    {
        session(['theme' => $request->theme]);

        return response()->json(['status' => 'success']);
    }


    /**
     * Get the admin theme preference
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getThemePreference(): JsonResponse
    {
        $theme = session('theme', 'light'); // Default to 'light' if no theme is set
        return response()->json(['theme' => $theme]);
    }



    public function all_instructors(): View {
        $instructors = User::where('role', 'instructor')->latest()->get();
        return view('admin.backend.instructor.all_instructors', compact('instructors'));
    }

    /**
     * Update instructor status
     *
     * @param string $id Instructor ID
     *
     * @return \Illuminate\Http\JsonResponse
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
            return response()->json(['error' => false , 'message' => $e->getMessage()], 500);
        }
    }


        
    public function become_instructor() {
        return view('frontend.instructor.become_instructor');
    }


    /**
     * Register a new instructor
     *
     * @param Request $request
     * @return void
     */
    public function instructor_register(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'photo' => ['nullable', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults(), 'min:8'],
        ]);

        // Save the instructor's photo if there is one

        if ($request->hasFile('photo')) {
            $data['photo'] = date('YmdHis') . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('public/upload/instructor_images', $data['photo']);
        }
        try{
            // Create and save the new instructor
            User::create([
                'name' => $validatedData['name'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'photo' => $validatedData['photo'],
                'address' => $validatedData['address'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'instructor',
                'status' => '0',
                'bio' => $request->bio,
            ]);

            $notification = array(
                'message' => 'Instructor registration successful. Please login to continue.',
                'alert-type' => 'success',
            );

            return redirect()->route('instructor.login')->with($notification);
        }catch(\Exception $e) {
            $notification = array(
                'message' => 'Something went wrong. Please try again.',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }


    public function all_courses() {
        $courses = Course::latest()->get();
        return view('admin.backend.course.all_courses', compact('courses'));
    }


    /**
     * Toggle the course status
     *
     * @param string $id Course ID
     * @return JsonResponse
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


    public function course_details(string $id) {
        $course = Course::find($id);
        return view('admin.backend.course.course_details', compact('course'));
    }

    public function all_admins() {
        $admins = User::where('role', 'admin')->get();

        return view('admin.backend.pages.admin.all_admins', compact('admins'));
    }

    public function add_admins() {
        $roles = Role::all();

        return view('admin.backend.pages.admin.add_admins', compact('roles'));
    }

    public function store_admin(Request $request) {


        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'string', 'max:20'],
                'photo' => ['nullable', 'max:2048'],
                'address' => ['required', 'string', 'max:255'],
                'password' => ['required', 'confirmed', Password::defaults(), 'min:8'],
            ]);
            $data['role'] = 'admin';
            $data['password'] = Hash::make($data['password']);
    
            $admin = User::create($data);
    
            $admin->assignRole($request->role);
    
            $notification = [
                'message' => 'Admin created successfully.',
                'alert-type' => 'success',
            ];
    
            return redirect()->route('admin.all_admins')->with($notification);

        } catch(Exception $e) {

            $notification = [
                'message' => 'Something went wrong. Please try again.',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification);
        }
    }

    public function edit_admin(string $id) {

        $admin = User::find($id);
        $roles = Role::all();
        return view('admin.backend.pages.admin.edit_admin', compact('admin', 'roles'));
    }

    public function update_admin(Request $request, string $id) {

        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$id],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
                'phone' => ['required', 'string', 'max:20'],
                'photo' => ['nullable', 'max:2048'],
                'address' => ['required', 'string', 'max:255'],
            ]);
            $data['role'] = 'admin';
    
            $admin = User::find($id);
            $admin->update($data);
    
            $admin->syncRoles($request->role);
    
            $notification = [
                'message' => 'Admin updated successfully.',
                'alert-type' => 'success',
            ];
    
            return redirect()->route('admin.all_admins')->with($notification);
        } catch(Exception $e) {

            $notification = [
                'message' => 'Something went wrong. Please try again.',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification);
        }
    }

    public function delete_admin(string $id) {

        try {
            User::find($id)->delete();

            $notification = array(
                'message' => 'Admin deleted successfully.',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);
        } catch(Exception $e) {

            $notification = array(
                'message' => 'Oops, something went wrong. Please try again',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }
}
