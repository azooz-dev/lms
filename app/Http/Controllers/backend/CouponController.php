<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function all_coupons() {
        $coupons = Coupon::latest()->get();
        return view('admin.backend.coupon.all_coupon', compact('coupons'));
    }

    public function add_coupon() {
        return view('admin.backend.coupon.add_coupon');
    }

    public function store_coupon(Request $request) {
        // Validate the request data.
        $data = $request->validate([
            'coupon_name' => 'required|unique:coupons',
            'coupon_discount' => 'required|numeric',
            'coupon_validity' => 'required',
        ]);

        // Convert the coupon_name to uppercase.
        $data['coupon_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] =  Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        // Create a new Coupon record with the validated data.
        Coupon::create($data);

        // Set the success notification message and type.
        $notifications = [
            'message' => 'Coupon added successfully.',
            'alert-type' => 'success'
        ];

        // Redirect back to the all_coupons view with the success notification.
        return redirect()->route('admin.all_coupons')->with($notifications);
    }

    public function edit_coupon(string $id) {
        $coupon = Coupon::find($id);
        return view('admin.backend.coupon.edit_coupon', compact('coupon'));
    }

    public function update_coupon(string $id, Request $request) {
        // Validate the request data.
        $data = $request->validate([
            'coupon_name' => 'required|unique:coupons,coupon_name,' . $id,
            'coupon_discount' => 'required|numeric',
            'coupon_validity' => 'required',
        ]);

        // Convert the coupon_name to uppercase.
        $data['course_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] =  Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        // Update the Coupon record with the validated data.
        Coupon::find($id)->update($data);

        // Set the success notification message and type.
        $notifications = [
            'message' => 'Coupon updated successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.all_coupons')->with($notifications);
    }


    public function destory_coupon(string $id) {
        Coupon::find($id)->delete();

        $notifications = [
            'message' => 'Coupon deleted successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.all_coupons')->with($notifications);
    }


    public function all_instructor_coupons(string $id) {
        $coupons = Coupon::where('instructor_id', $id)->latest()->get();

        return view('instructor.coupon.all_coupons', compact('coupons'));
    }


    public function add_instructor_coupon(string $id) {
        $courses = Course::where('instructor_id', $id)->latest()->get();
        return view('instructor.coupon.add_coupon', compact('courses'));
    }


    /**
     * Store a new instructor coupon.
     *
     * @param Request $request The HTTP request.
     * @param string $id The ID of the instructor.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_instructor_coupon(Request $request, string $id) {
        // Validate the request data.
        $data = $request->validate([
            'coupon_name' => 'required|unique:coupons', // Required and unique in the coupons table.
            'coupon_discount' => 'required|numeric', // Required and numeric.
            'coupon_validity' => 'required', // Required.
            'course_id' => 'required', // Required.
        ]);

        // Convert the coupon_name to uppercase.
        $data['coupon_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] =  Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        // Set the instructor_id to the given ID.
        $data['instructor_id'] = $id;

        // Create a new Coupon record with the validated data.
        Coupon::create($data);

        // Set the success notification message and type.
        $notifications = [
            'message' => 'Coupon added successfully.',
            'alert-type' => 'success'
        ];

        // Redirect back to the all_coupons view with the success notification.
        return redirect()->route('instructor.all_coupons', $id)->with($notifications);
    }


    public function edit_instructor_coupon(string $id) {
        $coupon = Coupon::find($id);
        $courses = Course::where('instructor_id', $coupon->instructor_id)->latest()->get();
        return view('instructor.coupon.edit_coupon', compact('coupon', 'courses'));
    }

    public function update_instructor_coupon(string $id, Request $request) {
        // Validate the request data.
        $data = $request->validate([
            'coupon_name' => 'required|unique:coupons,coupon_name,' . $id,
            'coupon_discount' => 'required|numeric',
            'coupon_validity' => 'required',
            'course_id' => 'required',
        ]);

        // Convert the coupon_name to uppercase.
        $data['coupon_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] =  \Carbon\Carbon::now()->format('Y-m-d H:i:s'); // Correctly formats the current time

        // Update the Coupon record with the validated data.
        Coupon::find($id)->update($data);

        // Set the success notification message and type.
        $notifications = [
            'message' => 'Coupon updated successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('instructor.all_coupons', Auth::user()->id)->with($notifications);
    }


    public function delete_instructor_coupon(string $id) {
        Coupon::find($id)->delete();

        $notifications = [
            'message' => 'Coupon deleted successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('instructor.all_coupons', Auth::user()->id)->with($notifications);
    }
}
