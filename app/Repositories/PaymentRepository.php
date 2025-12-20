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

    public function createWithInvoice(array $data): Payment
    {
        return Payment::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'cash_delivery' => $data['cash_delivery'],
            'total_amount' => $data['total_amount'],
            'payment_type' => 'Direct Payment',
            'status' => 'Pending',
            'invoice_number' => 'ESO'.mt_rand(10000000, 99999999),
        ]);
    }

    public function confirm(Payment $payment): void
    {
        $payment->update(['status' => 'Confirm']);
    }
}

