<?php

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\StoreInstructorCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Http\Requests\Coupon\UpdateInstructorCouponRequest;
use App\Models\Coupon;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function all_coupons()
    {
        $coupons = Coupon::latest()->get();

        return view('admin.backend.coupon.all_coupon', compact('coupons'));
    }

    public function add_coupon()
    {
        return view('admin.backend.coupon.add_coupon');
    }

    public function store_coupon(StoreCouponRequest $request)
    {
        $data = $request->validated();

        // Convert the coupon_name to uppercase.
        $data['coupon_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] = Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        // Create a new Coupon record with the validated data.
        Coupon::create($data);

        return redirect()
            ->route('admin.all_coupons')
            ->with(FlashNotification::success('Coupon added successfully.'));
    }

    public function edit_coupon(string $id)
    {
        $coupon = Coupon::find($id);

        return view('admin.backend.coupon.edit_coupon', compact('coupon'));
    }

    public function update_coupon(string $id, UpdateCouponRequest $request)
    {
        $data = $request->validated();

        // Convert the coupon_name to uppercase.
        $data['coupon_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] = Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        // Update the Coupon record with the validated data.
        Coupon::find($id)->update($data);

        return redirect()
            ->route('admin.all_coupons')
            ->with(FlashNotification::success('Coupon updated successfully.'));
    }

    public function destroy_coupon(string $id)
    {
        Coupon::find($id)->delete();

        return redirect()
            ->route('admin.all_coupons')
            ->with(FlashNotification::success('Coupon deleted successfully.'));
    }

    public function all_instructor_coupons(string $id)
    {
        $coupons = Coupon::where('instructor_id', $id)->latest()->get();

        return view('instructor.coupon.all_coupons', compact('coupons'));
    }

    public function add_instructor_coupon(string $id)
    {
        $courses = Course::where('instructor_id', $id)->latest()->get();

        return view('instructor.coupon.add_coupon', compact('courses'));
    }

    /**
     * Store a new instructor coupon.
     *
     * @param  StoreInstructorCouponRequest  $request  The validated request.
     * @param  string  $id  The ID of the instructor.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_instructor_coupon(StoreInstructorCouponRequest $request, string $id)
    {
        $data = $request->validated();

        // Convert the coupon_name to uppercase.
        $data['coupon_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] = Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        // Set the instructor_id to the given ID.
        $data['instructor_id'] = $id;

        // Create a new Coupon record with the validated data.
        Coupon::create($data);

        return redirect()
            ->route('instructor.all_coupons', $id)
            ->with(FlashNotification::success('Coupon added successfully.'));
    }

    public function edit_instructor_coupon(string $id)
    {
        $coupon = Coupon::find($id);
        $courses = Course::where('instructor_id', $coupon->instructor_id)->latest()->get();

        return view('instructor.coupon.edit_coupon', compact('coupon', 'courses'));
    }

    public function update_instructor_coupon(string $id, UpdateInstructorCouponRequest $request)
    {
        $data = $request->validated();

        // Convert the coupon_name to uppercase.
        $data['coupon_name'] = strtoupper($data['coupon_name']);

        // Convert the coupon_validity to a date format (Y-m-d).
        $data['coupon_validity'] = Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        // Update the Coupon record with the validated data.
        Coupon::find($id)->update($data);

        return redirect()
            ->route('instructor.all_coupons', Auth::user()->id)
            ->with(FlashNotification::success('Coupon updated successfully.'));
    }

    public function delete_instructor_coupon(string $id)
    {
        Coupon::find($id)->delete();

        return redirect()
            ->route('instructor.all_coupons', Auth::user()->id)
            ->with(FlashNotification::success('Coupon deleted successfully.'));
    }
}
