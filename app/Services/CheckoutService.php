<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Checkout\ProcessCheckoutAction;

class CheckoutService
{
    public function __construct(
        private readonly CouponService $couponService,
        private readonly ProcessCheckoutAction $processCheckoutAction
    ) {}

    /**
     * Calculate the total amount considering any applied coupon
     */
    public function calculateTotal(): float
    {
        return $this->couponService->getTotalWithCoupon();
    }

    /**
     * Process full checkout flow
     *
     * @return array{success: bool, message: string}
     */
    public function processCheckout(array $requestData, int $userId, bool $isCreditCard): array
    {
        return $this->processCheckoutAction->handle($requestData, $userId, $isCreditCard);
    }
}
