<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;
use App\Repositories\Contracts\OrderRepositoryInterface;

class CheckExistingOrder
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    /**
     * Check if user already has an order for any of the courses
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        $courseIds = $context->requestData['course_id'] ?? [];

        if ($this->orderRepository->existsForUserAndCourses($courseIds, $context->userId)) {
            return $context->fail('You have already enrolled in this course. Please check your order list.');
        }

        return $context;
    }
}
