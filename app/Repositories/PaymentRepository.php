<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Collection;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Payment);
    }

    public function getPendingPayments(): Collection
    {
        return Payment::where('status', 'pending')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getConfirmedPayments(): Collection
    {
        return Payment::where('status', 'Confirm')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function confirm(Payment $payment): void
    {
        $payment->update(['status' => 'Confirm']);
    }
}
