<?php

declare(strict_types=1);

namespace App\Payment\Processors;

use App\Payment\Contracts\PaymentProcessorInterface;
use App\Payment\DTO\PaymentData;
use App\Payment\DTO\PaymentResult;

class CashPaymentProcessor implements PaymentProcessorInterface
{
    /**
     * Process the cash payment
     * Cash payments are always "pending" until confirmed manually
     */
    public function process(PaymentData $data): PaymentResult
    {
        $transactionId = 'CASH_'.uniqid();

        return PaymentResult::success(
            $transactionId,
            'Cash payment submitted. Awaiting confirmation.'
        );
    }

    /**
     * Check if this processor supports the given payment type
     */
    public function supports(string $type): bool
    {
        return $type === 'cash' || $type === 'cash_delivery';
    }

    /**
     * Get the processor name
     */
    public function getName(): string
    {
        return 'cash';
    }
}
