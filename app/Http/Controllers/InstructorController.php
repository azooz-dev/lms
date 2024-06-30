<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    
    /**
     * Show the instructor dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Show the instructor dashboard page.
        return view('instructor.index');
    }

    /**
     * Log the instructor out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log the instructor out of the application.
        Auth::guard('web')->logout();

        // Invalidate the session data and regenerate the CSRF token.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect the instructor to the login page.
        return redirect('/instructor/login');
    }



    public function login() {
        return view('instructor.login_dashboard');
    }

    public function instructor_profile() {
        return view('instructor.instructor_profile');
    }

    /**
     * Update the instructor profile.
     *
     * @param  ProfileUpdateRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function instructor_update(ProfileUpdateRequest $request, string $id)
    {
        /** @var User $instructor */
        $instructor = User::find($id);

        /** @var array $data */
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            // Delete old photo if the file exists
            $this->deleteOldPhoto($instructor);

            // Store the new photo
            $data['photo'] = $this->storePhoto($request);
        } else {
            // Unset the photo if it is not provided
            unset($data['photo']);
        }

        // Update the instructor profile
        $instructor->update($data);

        $notification = [
            'message' => 'Instructor Profile Updated Successfully',
            'alert-type' => 'success',
        ];

        // Redirect the instructor back to their profile page
        return redirect()->back()->with($notification);
    }


    private function deleteOldPhoto(User $instructor)
    {
        // Check if the old photo exists in the storage
        if (!empty($instructor->photo) && Storage::exists('public/upload/instructor_images/' . $instructor->photo)) {
            // Delete the old photo from the storage
            Storage::delete('public/upload/instructor_images/' . $instructor->photo);
        }
    }


    private function storePhoto(Request $request) {
        // Generate a unique file name using the current date and time
        $fileName = date('YmdHis') . '_' . $request->file('photo')->getClientOriginalName();

        // Store the photo in the storage folder
        $request->file('photo')->storeAs('public/upload/instructor_images', $fileName);

        return $fileName;
    }


    public function change_password() {
        return view('instructor.change_password');
    }


    public function update_password(ChangePasswordRequest $request, string $id)
    {
        // Check if the old password is correct
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->with('error', 'The old password does not match.');
        }

        try {
            // Update the instructor password
            User::whereId($id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            $notification = array(
                'message' => "The Password changed successfully.",
                'alert-type' => 'success',
            );
            return back()->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => 'An error occurred while updating the password. Please try again later.',
                'alert-type' => 'error',
            );
            // Return an error message if an error occurred
            return back()->with($notification);
        }
    }


}
