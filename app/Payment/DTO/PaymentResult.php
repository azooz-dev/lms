<?php

declare(strict_types=1);

namespace App\Payment\DTO;

class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $transactionId = null,
        public readonly ?string $message = null,
        public readonly array $metadata = []
    ) {}

    /**
     * Create a successful payment result
     */
    public static function success(string $transactionId, string $message = 'Payment successful'): self
    {
        return new self(true, $transactionId, $message);
    }

    /**
     * Create a failed payment result
     */
    public static function failure(string $message): self
    {
        return new self(false, null, $message);
    }
}
