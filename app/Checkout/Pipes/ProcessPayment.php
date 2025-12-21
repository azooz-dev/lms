<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;
use App\Payment\DTO\PaymentData;
use App\Payment\PaymentProcessorFactory;

class ProcessPayment
{
    public function __construct(
        private readonly PaymentProcessorFactory $paymentFactory
    ) {}

    /**
     * Process the payment using the appropriate payment processor
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        try {
            $processor = $this->paymentFactory->make($context->paymentType);

            $cardData = null;
            if ($context->paymentType === 'credit_card' || $context->paymentType === 'stripe') {
                $cardData = [
                    'card_number' => $context->requestData['card_number'] ?? '',
                    'expiry_month' => $context->requestData['expiry_month'] ?? '',
                    'expiry_year' => $context->requestData['expiry_year'] ?? '',
                    'cvv' => $context->requestData['cardCVV'] ?? '',
                ];
            }

            $paymentData = new PaymentData(
                amount: $context->totalAmount,
                currency: 'usd',
                description: 'Course purchase',
                cardData: $cardData,
                email: $context->requestData['email'] ?? null,
                metadata: ['user_id' => $context->userId]
            );

            $result = $processor->process($paymentData);

            if (! $result->success) {
                return $context->fail($result->message ?? 'Payment failed.');
            }

            $context->transactionId = $result->transactionId;
            $context->setSuccessMessage($result->message ?? 'Payment successful.');

            return $context;
        } catch (\Exception $e) {
            return $context->fail('Payment failed: '.$e->getMessage());
        }
    }
}

