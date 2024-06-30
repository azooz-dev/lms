@extends('frontend.main')

@section('content')


@section('title')
Shopping Cart | Easy Learning
@endsection

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">Shopping Cart</h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="/">Home</a></li>
                <li>Shopping Cart</li>
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
<section class="cart-area section-padding">
    <div class="container">
        <div class="table-responsive">
            <table class="table generic-table">
                <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Course Details</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody id="tbody">

                </tbody>
            </table>
            <div class="d-flex flex-wrap align-items-center justify-content-between pt-4">
                <form action="#">
                    <div class="input-group">
                        <input class="form-control form--control pl-3 position-relative" type="text" name="search" placeholder="Coupon code" style="padding-right: 30px;" id="coupon_name">
                        <!-- Success Icon -->
                        <svg id="successIcon" style="width: 20px; position: absolute; right: 115px; top: 15px; display: none;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 50 50" xml:space="preserve" >
                            <circle style="fill:#25AE88;" cx="25" cy="25" r="25"/>
                            <polyline style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="38,15 22,33 12,25 "/>
                        </svg>
                        <!-- error Icon -->
                        <svg id="errorIcon" style="width: 20px; position: absolute; right: 135px; top: 15px; display: none;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 50 50" xml:space="preserve">
                            <circle style="fill:#D75A4A;" cx="25" cy="25" r="25"/>
                            <polyline style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;" points="16,34 25,25 34,16 "/>
                            <polyline style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;" points="16,16 25,25 34,34 "/>
                        </svg>
                        <div class="input-group-append">
                            <a type="submit" class="btn theme-btn" style="color: white;" id="applyCoupon" onclick="applyCoupon()">Apply Code</a>
                        </div>
                    </div>
                    <div id="divMessage">
                        
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4 ml-auto">
            <div class="bg-gray p-4 rounded-rounded mt-40px" id="cartTotal">
                <h3 class="fs-18 font-weight-bold pb-3">Cart Totals</h3>
                <div class="divider"><span></span></div>
                <ul class="generic-list-item pb-4">
                    <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                        <span class="text-black">Subtotal:</span>
                        <span id="subtotal"></span>
                    </li>
                    <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                        <span class="text-black">Total:</span>
                        <span id="total"></span>
                    </li>
                </ul>
                <a href="{{ route('checkout') }}" class="btn theme-btn w-100">Checkout <i class="la la-arrow-right icon ml-1"></i></a>
            </div>
        </div>
    </div><!-- end container -->
</section>
<!-- ================================
    END CONTACT AREA
================================= -->

@endsection