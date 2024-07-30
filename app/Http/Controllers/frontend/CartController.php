<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirm;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderComplate;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Token;

class CartController extends Controller
{
    /**
     * Add a course to the cart
     *
     * @param string $id The course id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function store_cart(string $id) {
        // Find the course
        $course = Course::find($id);

        // Check if the course exists in the cart
        $cartContent = Cart::content();
        $itemExists = $cartContent->contains(function ($value, $key) use ($id) {
            return $value->id == $id;
        });


        // If the course is already in the cart, return a response
        if ($itemExists) {
            return response()->json(['error' => 'The course is already in your cart.']);
        }

        // If the course is not in the cart, add it
        if ($course->discount_price > 0) {
            // Add the course to the cart with discount
            Cart::add([
                'id' => $course->id,
                'name' => $course->name,
                'qty' => 1,
                'price' => $course->discount_price,
                'weight' => 1,
                'options' => [
                    'slug' => $course->slug,
                    'image' => Storage::url("public/upload/course/images/{$course->image}"),
                    'instructor_id' => $course->instructor->id,
                    'instructor_name' => $course->instructor->name,
                    'selling_price' => $course->selling_price,
                ],
            ]);
        } else {
            // Add the course to the cart without discount
            Cart::add([
                'id' => $course->id,
                'name' => $course->name,
                'qty' => 1,
                'price' => $course->selling_price,
                'weight' => 1,
                'options' => [
                    'slug' => $course->slug,
                    'instructor_id' => $course->instructor->id,
                    'image' => Storage::url("public/upload/course/images/{$course->image}"),
                    'instructor' => $course->instructor->name,
                ],
            ]);
        }

        // Return a success response
        return response()->json(['success' => 'The course has been added to your cart.'], 200);
    }



    /**
     * Returns a JSON response with the mini cart content, total and count
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function mini_cart() {
        // Get the cart content
        $cartContent = Cart::content();

        // Get the cart total
        $cartTotal = Cart::total();

        // Get the cart count
        $cartCount = Cart::count();

        // Return a JSON response with the data
        return response()->json([
            'cartContent' => $cartContent,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ], 200);
    }


    /**
     * Remove a course from the mini cart
     *
     * @param string $id The course id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function mini_cart_delete(string $id) {
        // Remove the course from the cart
        Cart::remove($id);

        // Return a success response
        return response()->json(['success' => 'The course has been removed from your cart.'], 200);
    }

    public function show_cart() {
        return view('frontend.cart.my_cart');
    }

    public function cart_content() {
        // Get the cart content
        $cartContent = Cart::content();

        // Get the cart total
        $cartTotal = Cart::total();

        // Get the cart count
        $cartCount = Cart::count();

        // Return a JSON response with the data
        return response()->json([
            'cartContent' => $cartContent,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount,
            'coupon' => session()->has('coupon') ? session('coupon') : null
        ], 200);
    }


    public function remove_course_cart(string $id) {
        // Remove the course from the cart
        Cart::remove($id);

        if(session()->has('coupon')) {
            session()->forget('coupon');
        }

        // Return a success response
        return response()->json(['success' => 'The course has been removed from your cart.'], 200);
    }


    /**
     * Apply a coupon to the cart.
     *
     * @param Request $request The request object containing the coupon name.
     * @return \Illuminate\Http\JsonResponse The JSON response with the result of the application.
     */
    public function apply_coupon(Request $request) {

        // Clear existing coupon-related session data
        session()->forget('coupon');

        // Validate the request
        $request->validate([
            'coupon_name' => 'required',
        ]);

        // Get the coupon
        /** @var \App\Models\Coupon $coupon */
        $coupon = Coupon::where('coupon_name', $request->coupon_name)
            // Check if the coupon is valid
            ->where('coupon_validity', '>=', Carbon::now())
            ->first();

        $id = $request->query('id');
        $instructor = $request->query('instructor');
        // return response()->json([
        //     'coupon' => gettype($coupon->course_id) ,
        //     'id' => gettype($id),
        //     'test1' => intval($id),
        //     'test2' => intval($coupon->course_id),
        //     'check' => intval($coupon->course_id) == intval($id),
        // ]);
        // If the coupon is not found, return a response
        if (!$coupon) {
            // Return an error response if the coupon is not found
            return response()->json(['error' => 'coupon not found.'], 404);
        } elseif (intval($coupon->course_id) == intval($id)) {
            
            

            // Check if the coupon is valid for the course and instructor

                // Check if the cart is empty
                if(Cart::content()->isEmpty()) {
                    // Return an error response if the cart is empty
                    return response()->json(['error' => 'You must add the course to the cart before applying the coupon.']);
                }
                // If the coupon is valid, calculate the discount amount and total amount
                foreach(Cart::content() as $course) {
                    if ($course->id == $id && $course->options->instructor == $instructor) {
                        break;
                    }
                }
                if(empty($course)) {
                    // Return an error response if the cart does not contain the course
                    return response()->json(['error' => 'You must add the course to the cart before applying the coupon.']);
                }

                // Calculate the discount amount and total amount
                $discountAmount =  round(Cart::total() * $coupon->coupon_discount / 100);
                $totalAmount = ($course->discount_price > 0 ? round($course->discount_price - $discountAmount) : round($course->selling_price - $discountAmount));
                $removePrice = ($course->discount_price > 0 ? Cart::total() - $course->discount_price : Cart::total() - $course->selling_price);
                $totalAmount = $totalAmount + $removePrice;

                // Store the coupon details in the session
                session()->put('coupon', [
                    'coupon_name' => $coupon->coupon_name,
                    'coupon_discount' => $coupon->coupon_discount,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                ]);

                // Return a success response
                return response()->json([
                    'validity' => true,
                    'message' => 'Coupon applied successfully.',
                    'cartContent' => Cart::content()->isEmpty()
                ], 200);
            } else {
                // Return an error response if the coupon is not suitable for the course
                return response()->json(['error' => 'This coupon is not suitable for this course.']);
            } 
                // Calculate the discount amount and total amount
                $discountAmount = round(Cart::total() * $coupon->coupon_discount / 100);
                $totalAmount = round(Cart::total() - $discountAmount);

                // Store the coupon details in the session
                session()->put('coupon', [
                    'coupon_name' => $coupon->coupon_name,
                    'coupon_discount' => $coupon->coupon_discount,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                ]);

                // Return a success response
                return response()->json([
                    'validity' => true,
                    'message' => 'Coupon applied successfully.',
                ], 200);
    }

    public function cart_calculation() {
        // Check if a coupon is applied
        if (session()->has('coupon')) {
            // If a coupon is applied, return the calculation results
            return response()->json([
                'subTotal'        => Cart::total(),  // Total before discount
                'coupon_name'     => session()->get('coupon')['coupon_name'],  // Name of the applied coupon
                'coupon_discount' => session()->get('coupon')['coupon_discount'],  // Discount percentage
                'discount_amount' => session()->get('coupon')['discount_amount'],  // Discount amount
                'total_amount'    => Cart::total() - session()->get('coupon')['discount_amount'],  // Total after discount
            ]);
        } else {
            // If no coupon is applied, return the total cart amount
            return response()->json([
                'total' => Cart::total(),  // Total cart amount
            ]);
        }
    }

    public function remove_coupon() {
        session()->forget('coupon');

        return response()->json(['success' => 'Coupon removed successfully.']);
    }


    /**
     * Show the checkout view
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout() {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if there are items in the cart
            if (Cart::total() > 0) {
                // Get the cart content, total and count
                $cartContent = Cart::content();
                $cartTotal   = Cart::total();
                $cartCount   = Cart::count();

                // Load the checkout view with the cart data
                return view('frontend.checkout.checkout_view', compact('cartContent', 'cartTotal', 'cartCount'));
            } else {
                // If the cart is empty, show an error message
                $notification = [
                    'message' => 'Add at list one course',
                    'alert-type' => 'error'
                ];

                // Redirect to the homepage with the error message
                return redirect()->to('/')->with($notification);
            }
        } else {
            // If the user is not authenticated, show an error message
            $notification = [
                'message' => 'Please login first',
                'alert-type' => 'error'
            ];

            // Redirect to the login page with the error message
            return redirect()->to('/login')->with($notification);
        }
    }


    /**
     * Process payment for courses in the cart
     *
     * @param Request $request The request object containing the user's payment details
     *
     * @return \Illuminate\Http\Response
     */
    public function payment_process(Request $request) {
        // dd($request);
        // Check if a coupon is applied
        if (session()->has('coupon')) {
            // Get the total amount after applying the coupon
            $total_amount = session()->get('coupon')['total_amount'];
        } else {
            // Get the total amount without applying a coupon
            $total_amount = Cart::total();
        }

        // Check if an order with the same courses and user exists
        $existingOrder = Order::where(function ($query) use ($request) {
            $query->whereHas('course', function ($query) use ($request) {
                $query->whereIn('course_id', $request->course_id);
            })->where('user_id', Auth::user()->id)->where('is_visible_to_user', '1');
        })->first();

        if ($existingOrder) {
            // No API key provided. (HINT: set your API key using "Stripe::setApiKey(<API-KEY>)". You can generate API keys from the Stripe web interface. See https://stripe.com/api for details, or email support@stripe.com if you have any questions.
            // If an order with the same courses and user exists, show an error message
            $notification = [
                'message' => 'You have already enrolled in this course. Please check your order list.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        } else {
            // Check the payment method
            if ($request->cash_delivery == 'credit_card') {
                try {
                // Initialize Stripe client
                $apiKey = env('STRIPE_SECRET');
                $stripe = new StripeClient(['api_key' => $apiKey]); 

                // $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));
                // Validate and sanitize the input
                $validatedData = $request->validate([
                    'card_number' => '',
                    'expiry_month' => '',
                    'expiry_year' => '',
                    'cardCVV' => '',
                ]);

                
                    $token = Token::create([
                        'card' => [
                            'number' => $validatedData['card_number'],
                            'exp_month' => $validatedData['expiry_month'],
                            'exp_year' => $validatedData['expiry_year'],
                            'cvc' => $validatedData['cardCVV']
                        ]
                    ]); // Pass the user ID if available

                    // Charge the user's credit card
                    $stripe->charges->create([
                        'amount' => $total_amount * 100, // Stripe requires amount in cents
                        'currency' => 'usd', // Change to your currency
                        'source' => $token->id, // obtained with Stripe.js
                        'description' => 'Example charge'
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
                        'invoice_number' => 'ESO' . mt_rand(10000000, 99999999)
                    ]);

                    // Loop through the courses in the cart and create an order record for each course
                    foreach ($request->course_title as $key => $course_title) {
                        // Create an order record for the course
                        $course = array(
                            'payment_id' => $payment->id,
                            'course_id' => $request->course_id[$key],
                            'course_title' => $course_title,
                            'slug' => $request->slug[$key],
                            'course_image' => $request->image[$key],
                            'instructor_id' => $request->instructor_id[$key],
                            'course_price' => $request->price[$key],
                            'user_id' => Auth::user()->id
                        );

                        Order::create($course);
                    }

                    // Empty the cart
                    $request->session()->forget('cart');

                    // Send an order confirmation email to the user
                    Mail::to($request->email)->queue(new OrderConfirm($payment));

                    $notification = [
                        'message' => 'Payment successful.',
                        'alert-type' => 'success'
                    ];
                    return redirect()->route('index')->with($notification);
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                    // Handle error
                    $notification = [
                        'message' => $e->getMessage(),
                        'alert-type' => 'error'
                    ];
                    return back()->with($notification);
                }
            } else {
                // If the user selects cash delivery as the payment method, show a success message and redirect to the homepage

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
                    'invoice_number' => 'ESO' . mt_rand(10000000, 99999999)
                ]);

                // Loop through the courses in the cart and create an order record for each course  The cvv field is required.
                foreach ($request->course_title as $key => $course_title) {
                    // Create an order record for the course
                    $course = array(
                        'payment_id' => $payment->id,
                        'course_id' => $request->course_id[$key],
                        'course_title' => $course_title,
                        'slug' => $request->slug[$key],
                        'course_image' => $request->image[$key],
                        'instructor_id' => $request->instructor_id[$key],
                        'course_price' => $request->price[$key],
                        'user_id' => Auth::user()->id
                    );

                    Order::create($course);
                }

                // Empty the cart
                $request->session()->forget('cart');
                $request->session()->forget('coupon');

                // Send an order confirmation email to the user
                Mail::to($request->email)->queue(new OrderConfirm($payment));

                foreach($request->instructor_id as $instructor_id) {
                    $instructor = User::find($instructor_id);
                    $instructor->notify(new OrderComplate($request->name));
                }

                $request->session()->forget('cart');

                $notification = [
                    'message' => 'Cash Payment Submit Successfully.',
                    'alert-type' => 'success'
                ];
                return redirect()->route('index')->with($notification);
            }
        }
    }


    // The second argument to Stripe API method calls is an optional per-request apiKey, which must be a string, or per-request options, which must be an array. (HINT: you can set a global apiKey by "Stripe::setApiKey(<apiKey>)")
    /**
     * Add a course to the cart
     *
     * @param string $id The course id
     *
     * @return \Illuminate\Http\JsonResponse The JSON response with the success message
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function buy_course(string $id) {
        // Find the course
        $course = Course::find($id);

        // Check if the course exists in the cart
        $cartContent = Cart::content();
        $itemExists = $cartContent->contains(function ($value, $key) use ($id) {
            return $value->id == $id;
        });


        // If the course is already in the cart, return a response
        if ($itemExists) {
            return response()->json(['success' => 'The course is already in your cart.'], 200);
        }

        // If the course is not in the cart, add it
        if ($course->discount_price > 0) {
            // Add the course to the cart with discount
            Cart::add([
                'id' => $course->id,
                'name' => $course->name,
                'qty' => 1,
                'price' => $course->discount_price,
                'weight' => 1,
                'options' => [
                    'slug' => $course->slug,
                    'image' => Storage::url("public/upload/course/images/{$course->image}"),
                    'instructor_id' => $course->instructor->id,
                    'instructor_name' => $course->instructor->name,
                    'selling_price' => $course->selling_price,
                ],
            ]);
        } else {
            // Add the course to the cart without discount
            Cart::add([
                'id' => $course->id,
                'name' => $course->name,
                'qty' => 1,
                'price' => $course->selling_price,
                'weight' => 1,
                'options' => [
                    'slug' => $course->slug,
                    'image' => Storage::url("public/upload/course/images/{$course->image}"),
                    'instructor' => $course->instructor->name,
                ],
            ]);
        }

        // Return a success response
        return response()->json(['success' => 'The course has been added to your cart.'], 200);
    }

}
