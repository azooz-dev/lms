<?php

declare(strict_types=1);

namespace App\Payment\Contracts;

use App\Payment\DTO\PaymentData;
use App\Payment\DTO\PaymentResult;

interface PaymentProcessorInterface
{
    /**
     * Process the payment
     */
    public function process(PaymentData $data): PaymentResult;

    /**
     * Check if this processor supports the given payment type
     */
    public function supports(string $type): bool;

    /**
     * Get the processor name/identifier
     */
    public function getName(): string;
}
