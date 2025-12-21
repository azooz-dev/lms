<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;
use App\Services\CartService;

class ValidateCart
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    /**
     * Validate that the cart is not empty
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        if ($this->cartService->isEmpty()) {
            return $context->fail('Cart is empty.');
        }

        return $context;
    }
}

