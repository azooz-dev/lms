<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;
use App\Services\CouponService;

class CalculateTotal
{
    public function __construct(
        private readonly CouponService $couponService
    ) {}

    /**
     * Calculate the total amount considering any applied coupon
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        $context->totalAmount = $this->couponService->getTotalWithCoupon();

        return $context;
    }
}

