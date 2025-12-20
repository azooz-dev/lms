<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface extends RepositoryInterface
{
    public function getPendingPayments(): Collection;

    public function getConfirmedPayments(): Collection;

    public function createWithInvoice(array $data): Payment;

    public function confirm(Payment $payment): void;
}

