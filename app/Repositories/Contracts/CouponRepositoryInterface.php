<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Coupon;

interface CouponRepositoryInterface extends RepositoryInterface
{
    public function findValidByName(string $couponName): ?Coupon;

    public function isCouponValidForCourse(Coupon $coupon, int $courseId): bool;
}
