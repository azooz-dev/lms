<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

interface CouponRepositoryInterface extends RepositoryInterface
{
    public function getAllLatest(): Collection;

    public function getByInstructorId(int $instructorId): Collection;

    public function findValidByName(string $couponName): ?Coupon;

    public function isCouponValidForCourse(Coupon $coupon, int $courseId): bool;

    public function createCoupon(array $data): Coupon;

    public function updateCoupon(Coupon $coupon, array $data): Coupon;
}
