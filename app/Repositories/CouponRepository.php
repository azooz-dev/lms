<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Coupon);
    }

    public function getAllLatest(): Collection
    {
        return Coupon::latest()->get();
    }

    public function getByInstructorId(int $instructorId): Collection
    {
        return Coupon::where('instructor_id', $instructorId)->latest()->get();
    }

    public function findValidByName(string $couponName): ?Coupon
    {
        return Coupon::where('coupon_name', $couponName)
            ->where('coupon_validity', '>=', Carbon::now())
            ->first();
    }

    public function isCouponValidForCourse(Coupon $coupon, int $courseId): bool
    {
        return (int) $coupon->course_id === $courseId;
    }

    public function createCoupon(array $data): Coupon
    {
        $data['coupon_name'] = strtoupper($data['coupon_name']);
        $data['coupon_validity'] = Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        return Coupon::create($data);
    }

    public function updateCoupon(Coupon $coupon, array $data): Coupon
    {
        $data['coupon_name'] = strtoupper($data['coupon_name']);
        $data['coupon_validity'] = Carbon::parse($data['coupon_validity'])->format('Y-m-d');

        $coupon->update($data);

        return $coupon;
    }
}
