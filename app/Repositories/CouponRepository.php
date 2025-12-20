<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;
use Carbon\Carbon;

class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Coupon);
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
}
