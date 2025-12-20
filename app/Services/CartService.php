<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CartService
{
    /**
     * Add a course to the cart
     *
     * @return array{success: bool, message: string}
     */
    public function addCourse(Course $course): array
    {
        if ($this->courseExistsInCart($course->id)) {
            return [
                'success' => false,
                'message' => 'The course is already in your cart.',
            ];
        }

        $this->addCourseToCart($course);

        return [
            'success' => true,
            'message' => 'The course has been added to your cart.',
        ];
    }

    /**
     * Remove a course from the cart by row ID
     */
    public function removeCourse(string $rowId): void
    {
        Cart::remove($rowId);
    }

    /**
     * Remove a course from the cart and clear coupon
     */
    public function removeCourseAndClearCoupon(string $rowId): void
    {
        $this->removeCourse($rowId);

        if (session()->has('coupon')) {
            session()->forget('coupon');
        }
    }

    /**
     * Check if a course exists in the cart
     */
    public function courseExistsInCart(int $courseId): bool
    {
        return Cart::content()->contains(function ($item) use ($courseId) {
            return $item->id == $courseId;
        });
    }

    /**
     * Get the cart content
     */
    public function getContent(): Collection
    {
        return Cart::content();
    }

    /**
     * Get the cart total
     */
    public function getTotal(): float
    {
        return (float) Cart::total();
    }

    /**
     * Get the cart count
     */
    public function getCount(): int
    {
        return Cart::count();
    }

    /**
     * Get full cart data including content, total, count, and coupon
     */
    public function getCartData(): array
    {
        return [
            'cartContent' => $this->getContent(),
            'cartTotal' => $this->getTotal(),
            'cartCount' => $this->getCount(),
        ];
    }

    /**
     * Get cart data with coupon info
     */
    public function getCartDataWithCoupon(): array
    {
        return array_merge($this->getCartData(), [
            'coupon' => session()->has('coupon') ? session('coupon') : null,
        ]);
    }

    /**
     * Check if the cart is empty
     */
    public function isEmpty(): bool
    {
        return Cart::content()->isEmpty();
    }

    /**
     * Clear the cart
     */
    public function clear(): void
    {
        Cart::destroy();
    }

    /**
     * Find a course in the cart by ID
     */
    public function findCourseInCart(int $courseId, ?string $instructor = null): ?object
    {
        foreach (Cart::content() as $item) {
            if ($item->id == $courseId) {
                if ($instructor === null || $item->options->instructor == $instructor) {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * Add course to cart (internal helper)
     */
    private function addCourseToCart(Course $course): void
    {
        $price = $course->discount_price > 0 ? $course->discount_price : $course->selling_price;

        $options = [
            'slug' => $course->slug,
            'image' => Storage::url("public/upload/course/images/{$course->image}"),
            'instructor_id' => $course->instructor->id,
        ];

        if ($course->discount_price > 0) {
            $options['instructor_name'] = $course->instructor->name;
            $options['selling_price'] = $course->selling_price;
        } else {
            $options['instructor'] = $course->instructor->name;
        }

        Cart::add([
            'id' => $course->id,
            'name' => $course->name,
            'qty' => 1,
            'price' => $price,
            'weight' => 1,
            'options' => $options,
        ]);
    }
}

