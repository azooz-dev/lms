<?php

namespace App\Http\Controllers\frontend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\ApplyCouponRequest;
use App\Http\Requests\Cart\ProcessPaymentRequest;
use App\Mail\OrderConfirm;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderComplate;
use App\Services\CartService;
use App\Services\CouponService;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Stripe\StripeClient;
use Stripe\Token;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CouponService $couponService
    ) {}

    /**
     * Add a course to the cart
     *
     * @param  string  $id  The course id
     * @return JsonResponse
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
     * @return \Illuminate\Http\Response
     */
    public function payment_process(ProcessPaymentRequest $request)
    {
        // Check if a coupon is applied
        if (session()->has('coupon')) {
            $total_amount = session()->get('coupon')['total_amount'];
        } else {
            $total_amount = Cart::total();
        }

        // Check if an order with the same courses and user exists
        $existingOrder = Order::where(function ($query) use ($request) {
            $query->whereHas('course', function ($query) use ($request) {
                $query->whereIn('course_id', $request->course_id);
            })->where('user_id', Auth::user()->id)->where('is_visible_to_user', '1');
        })->first();

        if ($existingOrder) {
            return redirect()->back()->with(FlashNotification::error('You have already enrolled in this course. Please check your order list.'));
        } else {
            // Check the payment method
            if ($request->cash_delivery == 'credit_card') {
                try {
                    // Initialize Stripe client
                    $apiKey = env('STRIPE_SECRET');
                    $stripe = new StripeClient(['api_key' => $apiKey]);

                    $token = Token::create([
                        'card' => [
                            'number' => $request->card_number,
                            'exp_month' => $request->expiry_month,
                            'exp_year' => $request->expiry_year,
                            'cvc' => $request->cardCVV,
                        ],
                    ]);

                    // Charge the user's credit card
                    $stripe->charges->create([
                        'amount' => $total_amount * 100, // Stripe requires amount in cents
                        'currency' => 'usd',
                        'source' => $token->id,
                        'description' => 'Course purchase',
                    ]);

                    // Create a payment record with the user's details and total amount
                    $payment = Payment::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'cash_delivery' => $request->cash_delivery,
                        'total_amount' => $total_amount,
                        'payment_type' => 'Direct Payment',
                        'status' => 'Pending',
                        'invoice_number' => 'ESO'.mt_rand(10000000, 99999999),
                    ]);

                    // Loop through the courses in the cart and create an order record for each course
                    foreach ($request->course_title as $key => $course_title) {
                        $course = [
                            'payment_id' => $payment->id,
                            'course_id' => $request->course_id[$key],
                            'course_title' => $course_title,
                            'slug' => $request->slug[$key],
                            'course_image' => $request->image[$key],
                            'instructor_id' => $request->instructor_id[$key],
                            'course_price' => $request->price[$key],
                            'user_id' => Auth::user()->id,
                        ];

                        Order::create($course);
                    }

                    // Empty the cart
                    $request->session()->forget('cart');

                    // Send an order confirmation email to the user
                    Mail::to($request->email)->queue(new OrderConfirm($payment));

                    return redirect()->route('index')->with(FlashNotification::success('Payment successful.'));
                } catch (\Exception $e) {
                    return back()->with(FlashNotification::error('Payment failed: '.$e->getMessage()));
                }
            } else {
                // Cash delivery payment method
                $payment = Payment::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'cash_delivery' => $request->cash_delivery,
                    'total_amount' => $total_amount,
                    'payment_type' => 'Direct Payment',
                    'status' => 'Pending',
                    'invoice_number' => 'ESO'.mt_rand(10000000, 99999999),
                ]);

                foreach ($request->course_title as $key => $course_title) {
                    $course = [
                        'payment_id' => $payment->id,
                        'course_id' => $request->course_id[$key],
                        'course_title' => $course_title,
                        'slug' => $request->slug[$key],
                        'course_image' => $request->image[$key],
                        'instructor_id' => $request->instructor_id[$key],
                        'course_price' => $request->price[$key],
                        'user_id' => Auth::user()->id,
                    ];

                    Order::create($course);
                }

                $request->session()->forget('cart');
                $request->session()->forget('coupon');

                // Send an order confirmation email to the user
                Mail::to($request->email)->queue(new OrderConfirm($payment));

                foreach ($request->instructor_id as $instructor_id) {
                    $instructor = User::find($instructor_id);
                    $instructor->notify(new OrderComplate($request->name));
                }

                return redirect()->route('index')->with(FlashNotification::success('Cash Payment Submitted Successfully.'));
            }
        }
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
