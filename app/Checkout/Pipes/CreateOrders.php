<?php

declare(strict_types=1);

namespace App\Checkout\Pipes;

use App\Checkout\CheckoutContext;
use App\Repositories\Contracts\OrderRepositoryInterface;

class CreateOrders
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    /**
     * Create order records for each course in the cart
     */
    public function __invoke(CheckoutContext $context): CheckoutContext
    {
        $courses = $this->buildCoursesArray($context->requestData);

        foreach ($courses as $course) {
            $this->orderRepository->create([
                'payment_id' => $context->payment->id,
                'course_id' => $course['course_id'],
                'course_title' => $course['course_title'],
                'slug' => $course['slug'],
                'course_image' => $course['image'],
                'instructor_id' => $course['instructor_id'],
                'course_price' => $course['price'],
                'user_id' => $context->userId,
            ]);
        }

        $context->courses = $courses;

        return $context;
    }

    /**
     * Build courses array from request data
     */
    private function buildCoursesArray(array $requestData): array
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
}

