<?php

declare(strict_types=1);

namespace App\Payment\DTO;

class PaymentData
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $description,
        public readonly ?array $cardData = null,
        public readonly ?string $email = null,
        public readonly array $metadata = []
    ) {}
}
