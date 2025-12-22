<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Collection;

class CouponService
{
    public function __construct(
        private readonly CouponRepositoryInterface $couponRepository,
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    // Admin Coupon CRUD
    public function getAllCoupons(): Collection
    {
        return $this->couponRepository->getAllLatest();
    }

    public function findById(int $id): ?Coupon
    {
        return $this->couponRepository->find($id);
    }

    public function createCoupon(array $data): Coupon
    {
        $data = $this->transformCouponData($data);

        return $this->couponRepository->create($data);
    }

    public function updateCoupon(Coupon $coupon, array $data): Coupon
    {
        $data = $this->transformCouponData($data);

        return $this->couponRepository->update($coupon, $data);
    }

    public function deleteCoupon(Coupon $coupon): bool
    {
        return $this->couponRepository->delete($coupon);
    }

    // Instructor Coupon Operations
    public function getInstructorCoupons(int $instructorId): Collection
    {
        return $this->couponRepository->getByInstructorId($instructorId);
    }

    public function getInstructorCourses(int $instructorId): Collection
    {
        return $this->courseRepository->getByInstructorId($instructorId);
    }

    public function createInstructorCoupon(array $data, int $instructorId): Coupon
    {
        $data['instructor_id'] = $instructorId;
        $data = $this->transformCouponData($data);

        return $this->couponRepository->create($data);
    }

    /**
     * Validate and retrieve a coupon by name
     */
    public function validateCoupon(string $couponName): ?Coupon
    {
        return $this->couponRepository->findValidByName($couponName);
    }

    /**
     * Check if a coupon is valid for a specific course
     */
    public function isCouponValidForCourse(Coupon $coupon, int $courseId): bool
    {
        return $this->couponRepository->isCouponValidForCourse($coupon, $courseId);
    }

    /**
     * Calculate discount based on coupon and cart total
     *
     * @return array{coupon_name: string, coupon_discount: float, discount_amount: float, total_amount: float}
     */
    public function calculateDiscount(Coupon $coupon, float $cartTotal): array
    {
        $discountAmount = round($cartTotal * $coupon->coupon_discount / 100);
        $totalAmount = round($cartTotal - $discountAmount);

        return [
            'coupon_name' => $coupon->coupon_name,
            'coupon_discount' => (float) $coupon->coupon_discount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Calculate discount for a specific course in the cart
     *
     * @return array{coupon_name: string, coupon_discount: float, discount_amount: float, total_amount: float}
     */
    public function calculateCourseSpecificDiscount(Coupon $coupon, object $cartItem): array
    {
        $cartTotal = (float) Cart::total();
        $discountAmount = round($cartTotal * $coupon->coupon_discount / 100);

        $coursePrice = $cartItem->price;
        $courseTotalAfterDiscount = round($coursePrice - ($coursePrice * $coupon->coupon_discount / 100));
        $otherItemsTotal = $cartTotal - $coursePrice;
        $totalAmount = $courseTotalAfterDiscount + $otherItemsTotal;

        return [
            'coupon_name' => $coupon->coupon_name,
            'coupon_discount' => (float) $coupon->coupon_discount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Apply coupon data to session
     */
    public function applyCouponToSession(array $couponData): void
    {
        session()->put('coupon', $couponData);
    }

    /**
     * Remove coupon from session
     */
    public function removeCouponFromSession(): void
    {
        session()->forget('coupon');
    }

    /**
     * Get the currently applied coupon from session
     */
    public function getAppliedCoupon(): ?array
    {
        return session()->has('coupon') ? session('coupon') : null;
    }

    /**
     * Check if a coupon is currently applied
     */
    public function hasCouponApplied(): bool
    {
        return session()->has('coupon');
    }

    /**
     * Get cart calculation with or without coupon
     */
    public function getCartCalculation(): array
    {
        $cartTotal = (float) Cart::total();

        if ($this->hasCouponApplied()) {
            $coupon = $this->getAppliedCoupon();

            return [
                'subTotal' => $cartTotal,
                'coupon_name' => $coupon['coupon_name'],
                'coupon_discount' => $coupon['coupon_discount'],
                'discount_amount' => $coupon['discount_amount'],
                'total_amount' => $cartTotal - $coupon['discount_amount'],
            ];
        }

        return [
            'total' => $cartTotal,
        ];
    }

    /**
     * Get the total amount after applying coupon (if any)
     */
    public function getTotalWithCoupon(): float
    {
        if ($this->hasCouponApplied()) {
            return (float) session()->get('coupon')['total_amount'];
        }

        return (float) Cart::total();
    }

    /**
     * Transform coupon data with business logic (uppercase name, format date)
     */
    private function transformCouponData(array $data): array
    {
        if (isset($data['coupon_name'])) {
            $data['coupon_name'] = strtoupper($data['coupon_name']);
        }

        if (isset($data['coupon_validity'])) {
            $data['coupon_validity'] = Carbon::parse($data['coupon_validity'])->format('Y-m-d');
        }

        return $data;
    }
}
