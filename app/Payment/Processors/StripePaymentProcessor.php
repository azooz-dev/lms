<?php

declare(strict_types=1);

namespace App\Payment\Processors;

use App\Payment\Contracts\PaymentProcessorInterface;
use App\Payment\DTO\PaymentData;
use App\Payment\DTO\PaymentResult;
use Stripe\StripeClient;
use Stripe\Token;

class StripePaymentProcessor implements PaymentProcessorInterface
{
    private StripeClient $stripe;

    public function __construct()
    {
        $apiKey = config('services.stripe.secret') ?? env('STRIPE_SECRET');
        $this->stripe = new StripeClient(['api_key' => $apiKey]);
    }

    /**
     * Process the Stripe payment
     */
    public function process(PaymentData $data): PaymentResult
    {
        try {
            if (empty($data->cardData)) {
                return PaymentResult::failure('Card data is required for Stripe payments.');
            }

            $token = Token::create([
                'card' => [
                    'number' => $data->cardData['card_number'],
                    'exp_month' => $data->cardData['expiry_month'],
                    'exp_year' => $data->cardData['expiry_year'],
                    'cvc' => $data->cardData['cvv'],
                ],
            ]);

            $charge = $this->stripe->charges->create([
                'amount' => (int) ($data->amount * 100), // Stripe requires amount in cents
                'currency' => $data->currency,
                'source' => $token->id,
                'description' => $data->description,
                'metadata' => $data->metadata,
            ]);

            return PaymentResult::success($charge->id, 'Payment processed successfully.');
        } catch (\Exception $e) {
            return PaymentResult::failure('Stripe payment failed: '.$e->getMessage());
        }
    }

    /**
     * Check if this processor supports the given payment type
     */
    public function supports(string $type): bool
    {
        return $type === 'stripe' || $type === 'credit_card';
    }

    /**
     * Get the processor name
     */
    public function getName(): string
    {
        return 'stripe';
    }
}

