<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Events\OrderConfirmed;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class ConfirmOrderAction
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Confirm an order by updating payment status and dispatching event
     */
    public function handle(Payment $payment): void
    {
        $this->paymentRepository->confirm($payment);

        // Dispatch event to handle notifications
        OrderConfirmed::dispatch($payment);
    }
}
