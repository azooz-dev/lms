<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Payment;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Stripe\StripeClient;
use Stripe\Token;

class CheckoutService
{
    public function __construct(
        private readonly CouponService $couponService,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Calculate the total amount considering any applied coupon
     */
    public function calculateTotal(): float
    {
        return $this->couponService->getTotalWithCoupon();
    }

    /**
     * Check if user has existing order for any of the courses
     */
    public function hasExistingOrder(array $courseIds, int $userId): bool
    {
        return $this->orderRepository->existsForUserAndCourses($courseIds, $userId);
    }

    /**
     * Process payment via Stripe
     *
     * @throws \Exception
     */
    public function processStripePayment(array $cardData, float $amount): void
    {
        $apiKey = env('STRIPE_SECRET');
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

    /**
     * Create a payment record
     */
    public function createPayment(array $data): Payment
    {
        return $this->paymentRepository->createWithInvoice($data);
    }

    /**
     * Create order records for each course
     */
    public function createOrders(Payment $payment, array $courses, int $userId): void
    {
        foreach ($courses as $course) {
            $this->orderRepository->create([
                'payment_id' => $payment->id,
                'course_id' => $course['course_id'],
                'course_title' => $course['course_title'],
                'slug' => $course['slug'],
                'course_image' => $course['image'],
                'instructor_id' => $course['instructor_id'],
                'course_price' => $course['price'],
                'user_id' => $userId,
            ]);
        }
    }

    /**
     * Dispatch order placed event to handle email and notifications
     */
    public function dispatchOrderPlacedEvent(
        Payment $payment,
        string $customerEmail,
        array $instructorIds,
        string $customerName
    ): void {
        OrderPlaced::dispatch($payment, $customerEmail, $instructorIds, $customerName);
    }

    /**
     * Clear session data after successful checkout
     */
    public function clearCheckoutSession(): void
    {
        session()->forget('cart');
        session()->forget('coupon');
    }

    /**
     * Build courses array from request data
     */
    public function buildCoursesArray(array $requestData): array
    {
        $courses = [];

        foreach ($requestData['course_title'] as $key => $courseTitle) {
            $courses[] = [
                'course_id' => $requestData['course_id'][$key],
                'course_title' => $courseTitle,
                'slug' => $requestData['slug'][$key],
                'image' => $requestData['image'][$key],
                'instructor_id' => $requestData['instructor_id'][$key],
                'price' => $requestData['price'][$key],
            ];
        }

        return $courses;
    }

    /**
     * Process full checkout flow
     *
     * @return array{success: bool, message: string}
     */
    public function processCheckout(array $requestData, int $userId, bool $isCreditCard): array
    {
        $totalAmount = $this->calculateTotal();

        // Check for existing orders
        if ($this->hasExistingOrder($requestData['course_id'], $userId)) {
            return [
                'success' => false,
                'message' => 'You have already enrolled in this course. Please check your order list.',
            ];
        }

        try {
            // Process Stripe payment if credit card
            if ($isCreditCard) {
                $this->processStripePayment([
                    'card_number' => $requestData['card_number'],
                    'expiry_month' => $requestData['expiry_month'],
                    'expiry_year' => $requestData['expiry_year'],
                    'cvv' => $requestData['cardCVV'],
                ], $totalAmount);
            }

            // Create payment record
            $payment = $this->createPayment([
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'address' => $requestData['address'],
                'cash_delivery' => $requestData['cash_delivery'],
                'total_amount' => $totalAmount,
            ]);

            // Create order records
            $courses = $this->buildCoursesArray($requestData);
            $this->createOrders($payment, $courses, $userId);

            // Dispatch event to handle email and notifications
            $this->dispatchOrderPlacedEvent(
                $payment,
                $requestData['email'],
                $requestData['instructor_id'],
                $requestData['name']
            );

            // Clear session
            $this->clearCheckoutSession();

            $message = $isCreditCard ? 'Payment successful.' : 'Cash Payment Submitted Successfully.';

            return [
                'success' => true,
                'message' => $message,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Payment failed: '.$e->getMessage(),
            ];
        }
    }
}
