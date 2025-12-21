<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;

class ClearCheckoutSession
{
    /**
     * Clear the cart and coupon from the session
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        session()->forget('cart');
        session()->forget('coupon');

        return $context;
    }
}

