<?php

namespace App\Http\Controllers\frontend;

use App\Actions\Checkout\ProcessCheckoutAction;
use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\ApplyCouponRequest;
use App\Http\Requests\Cart\ProcessPaymentRequest;
use App\Models\Course;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CouponService $couponService,
        private readonly ProcessCheckoutAction $processCheckoutAction
    ) {}

    /**
     * Add a course to the cart
     *
     * @param  string  $id  The course id
     */
    public function store_cart(string $id): JsonResponse
    {
        $course = Course::find($id);
        $result = $this->cartService->addCourse($course);

        if ($result['success']) {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']]);
    }

    /**
     * Returns a JSON response with the mini cart content, total and count
     */
    public function mini_cart(): JsonResponse
    {
        return response()->json($this->cartService->getCartData(), 200);
    }

    /**
     * Remove a course from the mini cart
     *
     * @param  string  $id  The row id
     */
    public function mini_cart_delete(string $id): JsonResponse
    {
        $this->cartService->removeCourse($id);

        return response()->json(['success' => 'The course has been removed from your cart.'], 200);
    }

    public function show_cart()
    {
        return view('frontend.cart.my_cart');
    }

    public function cart_content(): JsonResponse
    {
        return response()->json($this->cartService->getCartDataWithCoupon(), 200);
    }

    public function remove_course_cart(string $id): JsonResponse
    {
        $this->cartService->removeCourseAndClearCoupon($id);

        return response()->json(['success' => 'The course has been removed from your cart.'], 200);
    }

    /**
     * Apply a coupon to the cart.
     *
     * @param  ApplyCouponRequest  $request  The validated request object containing the coupon name.
     */
    public function apply_coupon(ApplyCouponRequest $request): JsonResponse
    {
        // Clear existing coupon
        $this->couponService->removeCouponFromSession();

        // Validate the coupon
        $coupon = $this->couponService->validateCoupon($request->coupon_name);
        if (! $coupon) {
            return response()->json(['error' => 'Coupon not found.'], 404);
        }

        $courseId = (int) $request->query('id');
        $instructor = $request->query('instructor');

        // Check if coupon is valid for the specific course
        if (! $this->couponService->isCouponValidForCourse($coupon, $courseId)) {
            return response()->json(['error' => 'This coupon is not suitable for this course.']);
        }

        // Check if cart is empty
        if ($this->cartService->isEmpty()) {
            return response()->json(['error' => 'You must add the course to the cart before applying the coupon.']);
        }

        // Find the course in cart
        $cartItem = $this->cartService->findCourseInCart($courseId, $instructor);
        if (! $cartItem) {
            return response()->json(['error' => 'You must add the course to the cart before applying the coupon.']);
        }

        // Calculate and apply discount
        $couponData = $this->couponService->calculateCourseSpecificDiscount($coupon, $cartItem);
        $this->couponService->applyCouponToSession($couponData);

        return response()->json([
            'validity' => true,
            'message' => 'Coupon applied successfully.',
            'cartContent' => $this->cartService->isEmpty(),
        ], 200);
    }

    public function cart_calculation(): JsonResponse
    {
        return response()->json($this->couponService->getCartCalculation());
    }

    public function remove_coupon(): JsonResponse
    {
        $this->couponService->removeCouponFromSession();

        return response()->json(['success' => 'Coupon removed successfully.']);
    }

    /**
     * Show the checkout view
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function checkout()
    {
        if (! Auth::check()) {
            return redirect()->to('/login')->with(FlashNotification::error('Please login first.'));
        }

        if ($this->cartService->isEmpty()) {
            return redirect()->to('/')->with(FlashNotification::error('Add at least one course.'));
        }

        $cartData = $this->cartService->getCartData();

        return view('frontend.checkout.checkout_view', $cartData);
    }

    /**
     * Process payment for courses in the cart
     *
     * @param  ProcessPaymentRequest  $request  The validated request object containing the user's payment details
     */
    public function payment_process(ProcessPaymentRequest $request): RedirectResponse
    {
        $isCreditCard = $request->cash_delivery === 'credit_card';

        $result = $this->processCheckoutAction->handle(
            $request->all(),
            Auth::id(),
            $isCreditCard
        );

        if ($result['success']) {
            return redirect()->route('index')->with(FlashNotification::success($result['message']));
        }

        return redirect()->back()->with(FlashNotification::error($result['message']));
    }

    /**
     * Add a course to the cart (buy now)
     *
     * @param  string  $id  The course id
     */
    public function buy_course(string $id): JsonResponse
    {
        $course = Course::find($id);
        $result = $this->cartService->addCourse($course);

        // For buy_course, we always return success even if already in cart
        return response()->json(['success' => $result['message']], 200);
    }
}
