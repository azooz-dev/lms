<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class CreatePaymentRecord
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Create the payment record in the database
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        $context->payment = $this->paymentRepository->create([
            'name' => $context->requestData['name'],
            'email' => $context->requestData['email'],
            'phone' => $context->requestData['phone'],
            'address' => $context->requestData['address'],
            'cash_delivery' => $context->requestData['cash_delivery'],
            'total_amount' => $context->totalAmount,
            'payment_type' => 'Direct Payment',
            'status' => 'Pending',
            'invoice_number' => $this->generateInvoiceNumber(),
            'transaction_id' => $context->transactionId,
        ]);

        return $context;
    }

    /**
     * Generate a unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        return 'ESO'.mt_rand(10000000, 99999999);
    }
}
