<?php

declare(strict_types=1);

namespace App\Services;

use App\Checkout\CheckoutContext;
use App\Checkout\CheckoutPipeline;
use App\Checkout\Pipes\CalculateTotal;
use App\Checkout\Pipes\CheckExistingOrder;
use App\Checkout\Pipes\ClearCheckoutSession;
use App\Checkout\Pipes\CreateOrders;
use App\Checkout\Pipes\CreatePaymentRecord;
use App\Checkout\Pipes\DispatchOrderEvents;
use App\Checkout\Pipes\ProcessPayment;
use App\Checkout\Pipes\ValidateCart;
use App\Payment\PaymentProcessorFactory;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class CheckoutService
{
    public function __construct(
        private readonly CouponService $couponService,
        private readonly CartService $cartService,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly PaymentProcessorFactory $paymentFactory
    ) {}

    /**
     * Calculate the total amount considering any applied coupon
     */
    public function calculateTotal(): float
    {
        return $this->couponService->getTotalWithCoupon();
    }

    /**
     * Process full checkout flow using the pipeline pattern
     *
     * @return array{success: bool, message: string}
     */
    public function processCheckout(array $requestData, int $userId, bool $isCreditCard): array
    {
        $paymentType = $isCreditCard ? 'credit_card' : 'cash';
        $context = new CheckoutContext($requestData, $userId, $paymentType);

        try {
            $pipeline = new CheckoutPipeline;

            $result = $pipeline
                ->pipe(new ValidateCart($this->cartService))
                ->pipe(new CheckExistingOrder($this->orderRepository))
                ->pipe(new CalculateTotal($this->couponService))
                ->pipe(new ProcessPayment($this->paymentFactory))
                ->pipe(new CreatePaymentRecord($this->paymentRepository))
                ->pipe(new CreateOrders($this->orderRepository))
                ->pipe(new DispatchOrderEvents)
                ->pipe(new ClearCheckoutSession)
                ->process($context);

            if ($result->hasFailed()) {
                return [
                    'success' => false,
                    'message' => $result->getError(),
                ];
            }

            return [
                'success' => true,
                'message' => $result->getSuccessMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Checkout failed: '.$e->getMessage(),
            ];
        }
    }
}
