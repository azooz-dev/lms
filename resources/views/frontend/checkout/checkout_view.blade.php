@extends('frontend.main')

@section('content')


@section('title')
Checkout | Easy Learning
@endsection


<!-- ================================
        START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">Checkout</h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="index.html">Home</a></li>
                <li>Pages</li>
                <li>Checkout</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
        END BREADCRUMB AREA
================================= -->

<!-- ================================
        START CONTACT AREA
================================= -->
<section class="cart-area section--padding">
    <form method="POST" action="{{ route('payment.process') }}" id="payment-form" class="row">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-22 pb-3">Billing Details</h3>
                            <div class="divider"><span></span></div>
                                <div class="input-box col-lg-12">
                                    <label class="label-text">Name</label>
                                    <div class="form-group">
                                        <input class="form-control form--control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Enter your full name">
                                        <span class="la la-user input-icon"></span>
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end input-box -->

                                <div class="input-box col-lg-12">
                                    <label class="label-text">Email</label>
                                    <div class="form-group">
                                        <input class="form-control form--control @error('email') is-invalid @enderror" type="email" name="email" id="email" placeholder="Enter your email address">
                                        <span class="la la-envelope input-icon"></span>
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end input-box -->

                                <div class="input-box col-lg-12">
                                    <label class="label-text">Phone Number</label>
                                    <div class="form-group">
                                        <input class="form-control form--control @error('phone') is-invalid @enderror" type="text" name="phone" id="phone" placeholder="Enter your phone number">
                                        <span class="fas fa-phone input-icon" style="top:18px;left:18px; font-size:14px;"></span>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end input-box -->

                                <div class="input-box col-lg-12">
                                    <label class="label-text">Address</label>
                                    <div class="form-group">
                                        <input class="form-control form--control @error('address') is-invalid @enderror" id="address" type="text" name="address" placeholder="Enter your home address">
                                        <span class="la la-map-marker input-icon"></span>
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end input-box -->
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    
                        <div class="card card-item">
                            <div class="card-body">
                                <h3 class="card-title fs-22 pb-3">Select Payment Method</h3>
                                <div class="divider"><span></span></div>
                                <div class="payment-option-wrap">
                                    <div class="payment-tab">
                                        <div class="payment-tab-toggle">
                                            <input type="radio" name="cash_delivery" id="creditCart" value="credit_card">
                                            <label for="creditCart">Credit / Debit Card</label>
                                            <img class="payment-logo" src="{{ asset('frontend/images/payment-img.png') }}" alt="">
                                        </div>
                                        <div class="payment-tab-content d-flex flex-wrap">
                                            <div class="input-box col-lg-6">
                                                <label class="label-text">Name on Card</label>
                                                <div class="form-group">
                                                    <input class="form-control form--control pl-3" type="text" id="card_name" name="card_name" placeholder="Card Name">
                                                </div>
                                            </div>
                                            <div class="input-box col-lg-6">
                                                <label class="label-text">Card Number</label>
                                                <div class="form-group">
                                                    <input class="form-control form--control pl-3" type="text" id="card_number" name="card_number" placeholder="1234  5678  9876  5432">
                                                </div>
                                            </div> 
                                            <div class="input-box col-lg-4">
                                                <label class="label-text">Expiry Month</label>
                                                <div class="form-group">
                                                    <input class="form-control form--control pl-3" type="text"  id="expiry_month" name="expiry_month"  placeholder="MM">
                                                </div>
                                            </div>
                                            <div class="input-box col-lg-4">
                                                <label class="label-text">Expiry Year</label>
                                                <div class="form-group">
                                                    <input class="form-control form--control pl-3"id="expiry_year" name="expiry_year" placeholder="YY">
                                                </div>
                                            </div>
                                            <div class="input-box col-lg-4">
                                                <label class="label-text">CVV</label>
                                                <div class="form-group">
                                                    <input class="form-control form--control pl-3" id="cvv" name="cardCVV" placeholder="cvv">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end payment-tab -->
                                    <div class="payment-tab">
                                        <div class="payment-tab-toggle">
                                            <input name="cash_delivery" id="DirectPayment" type="radio" value="handcash">
                                            <label for="DirectPayment">Direct Payment</label>
                                        </div>
                                        
                                    </div><!-- end payment-tab -->
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col-lg-7 -->
                    <div class="col-lg-5">
                        <div class="card card-item">
                            <div class="card-body">
                                <h3 class="card-title fs-22 pb-3">Order Details</h3>
                                <div class="divider"><span></span></div>
                                <div class="order-details-lists">
                                    @foreach ($cartContent as $course)

                                    <input type="hidden" name="slug[]" value="{{ $course->options->slug }}">
                                    <input type="hidden" name="course_title[]" value="{{ $course->name }}">
                                    <input type="hidden" name="image[]" value="{{ $course->options->image }}">
                                    <input type="hidden" name="course_id[]" value="{{ $course->id }}">
                                    <input type="hidden" name="instructor_id[]" value="{{ $course->options->instructor_id }}">
                                    <input type="hidden" name="price[]" value="{{ $course->price }}">
                                    
                                        <div class="media media-card border-bottom border-bottom-gray pb-3 mb-3">
                                            <a href="{{ route('course_details', ['id' => $course->id, 'slug' => $course->options->slug]) }}" class="media-img">
                                                <img src="{{ $course->options->image }}" alt="Cart image">
                                            </a>
                                            <div class="media-body">
                                                <h5 class="fs-15 pb-2"><a href="{{ route('course_details', ['id' => $course->id, 'slug' => $course->options->slug]) }}">{{ $course->name }}</a></h5>
                                                @if ($course->options->selling_price)
                                                <p class="text-black font-weight-semi-bold lh-18">${{ $course->price }} <span class="before-price fs-14">${{ $course->options->selling_price }}</span></p>
                                                @else
                                                <p class="text-black font-weight-semi-bold lh-18">${{ $course->price }}</p>
                                                @endif
                                            </div>
                                        </div><!-- end media -->
                                    @endforeach

                                </div><!-- end order-details-lists -->
                                <a href="{{ route('show_cart') }}" class="btn-text"><i class="la la-edit mr-1"></i>Edit</a>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                        <div class="card card-item">
                            <div class="card-body">
                                <h3 class="card-title fs-22 pb-3">Order Summary</h3>
                                <div class="divider"><span></span></div>
                                <ul class="generic-list-item generic-list-item-flash fs-15">
                                    @if (Session::has('coupon'))
                                        <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                            <span class="text-black">SubTotal:</span>
                                            <span>${{ $cartTotal }}</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                            <span class="text-black">Coupon discounts:</span>
                                            <span>${{ Session::get('coupon')['discount_amount'] }}</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-between font-weight-bold">
                                            <span class="text-black">Total:</span>
                                            <span>${{ Session::get('coupon')['total_amount'] }}</span>
                                        </li>
                                        @else
                                        <li class="d-flex align-items-center justify-content-between font-weight-bold">
                                            <span class="text-black">Total:</span>
                                            <span>${{ $cartTotal }}</span>
                                        </li>
                                    @endif
                                </ul>
                                <div class="btn-box border-top border-top-gray pt-3">
                                    <p class="fs-14 lh-22 mb-2">Aduca is required by law to collect applicable transaction taxes for purchases made in certain tax jurisdictions.</p>
                                    <p class="fs-14 lh-22 mb-3">By completing your purchase you agree to these <a href="#" class="text-color hover-underline">Terms of Service.</a></p>
                                    <button type="submit" class="btn theme-btn w-100">Proceed <i class="la la-arrow-right icon ml-1"></i></button>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </form>
                    </div><!-- end col-lg-5 -->
                </div><!-- end row -->
            </div><!-- end container -->
    
</section>
<!-- ================================
        END CONTACT AREA
================================= -->



@endsection