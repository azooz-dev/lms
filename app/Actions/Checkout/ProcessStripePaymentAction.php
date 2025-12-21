<?php

declare(strict_types=1);

namespace App\Actions\Checkout;

use Stripe\StripeClient;
use Stripe\Token;

class ProcessStripePaymentAction
{
    /**
     * Process a Stripe payment
     *
     * @param  array{card_number: string, expiry_month: string, expiry_year: string, cvv: string}  $cardData
     *
     * @throws \Exception
     */
    public function handle(array $cardData, float $amount): void
    {
        $apiKey = config('services.stripe.secret') ?? env('STRIPE_SECRET');
        $stripe = new StripeClient(['api_key' => $apiKey]);

        $token = Token::create([
            'card' => [
                'number' => $cardData['card_number'],
                'exp_month' => $cardData['expiry_month'],
                'exp_year' => $cardData['expiry_year'],
                'cvc' => $cardData['cvv'],
            ],
        ]);

        $stripe->charges->create([
            'amount' => (int) ($amount * 100), // Stripe requires amount in cents
            'currency' => 'usd',
            'source' => $token->id,
            'description' => 'Course purchase',
        ]);
    }
}
