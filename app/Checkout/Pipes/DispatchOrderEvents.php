<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;
use App\Events\OrderPlaced;

class DispatchOrderEvents
{
    /**
     * Dispatch the OrderPlaced event to handle email and notifications
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        OrderPlaced::dispatch(
            $context->payment,
            $context->requestData['email'],
            $context->requestData['instructor_id'],
            $context->requestData['name']
        );

        return $context;
    }
}
