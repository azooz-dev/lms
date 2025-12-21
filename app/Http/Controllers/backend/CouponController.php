<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\StoreInstructorCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Http\Requests\Coupon\UpdateInstructorCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function __construct(
        private readonly CouponService $couponService
    ) {}

    public function all_coupons(): View
    {
        $coupons = $this->couponService->getAllCoupons();

        return view('admin.backend.coupon.all_coupon', compact('coupons'));
    }

    public function add_coupon(): View
    {
        return view('admin.backend.coupon.add_coupon');
    }

    public function store_coupon(StoreCouponRequest $request): RedirectResponse
    {
        $this->couponService->createCoupon($request->validated());

        return redirect()
            ->route('admin.all_coupons')
            ->with(FlashNotification::success('Coupon added successfully.'));
    }

    public function edit_coupon(string $id): View
    {
        $coupon = $this->couponService->findById((int) $id);

        return view('admin.backend.coupon.edit_coupon', compact('coupon'));
    }

    public function update_coupon(string $id, UpdateCouponRequest $request): RedirectResponse
    {
        $coupon = $this->couponService->findById((int) $id);
        $this->couponService->updateCoupon($coupon, $request->validated());

        return redirect()
            ->route('admin.all_coupons')
            ->with(FlashNotification::success('Coupon updated successfully.'));
    }

    public function destroy_coupon(string $id): RedirectResponse
    {
        $coupon = $this->couponService->findById((int) $id);
        $this->couponService->deleteCoupon($coupon);

        return redirect()
            ->route('admin.all_coupons')
            ->with(FlashNotification::success('Coupon deleted successfully.'));
    }

    public function all_instructor_coupons(string $id): View
    {
        $coupons = $this->couponService->getInstructorCoupons((int) $id);

        return view('instructor.coupon.all_coupons', compact('coupons'));
    }

    public function add_instructor_coupon(string $id): View
    {
        $courses = $this->couponService->getInstructorCourses((int) $id);

        return view('instructor.coupon.add_coupon', compact('courses'));
    }

    public function store_instructor_coupon(StoreInstructorCouponRequest $request, string $id): RedirectResponse
    {
        $this->couponService->createInstructorCoupon($request->validated(), (int) $id);

        return redirect()
            ->route('instructor.all_coupons', $id)
            ->with(FlashNotification::success('Coupon added successfully.'));
    }

    public function edit_instructor_coupon(string $id): View
    {
        $coupon = $this->couponService->findById((int) $id);
        $courses = $this->couponService->getInstructorCourses($coupon->instructor_id);

        return view('instructor.coupon.edit_coupon', compact('coupon', 'courses'));
    }

    public function update_instructor_coupon(string $id, UpdateInstructorCouponRequest $request): RedirectResponse
    {
        $coupon = $this->couponService->findById((int) $id);
        $this->couponService->updateCoupon($coupon, $request->validated());

        return redirect()
            ->route('instructor.all_coupons', Auth::user()->id)
            ->with(FlashNotification::success('Coupon updated successfully.'));
    }

    public function delete_instructor_coupon(string $id): RedirectResponse
    {
        $coupon = $this->couponService->findById((int) $id);
        $this->couponService->deleteCoupon($coupon);

        return redirect()
            ->route('instructor.all_coupons', Auth::user()->id)
            ->with(FlashNotification::success('Coupon deleted successfully.'));
    }
}
