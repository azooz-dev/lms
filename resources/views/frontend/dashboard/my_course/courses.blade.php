@extends('frontend.dashboard.dashboard_user')

@section('user_dashboard')


@section('title_dashboard')
My Courses | Easy Learning
@endsection


<div class="container-fluid">
    <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between mb-5">

    </div><!-- end breadcrumb-content -->
    <div class="section-block mb-5"></div>
    <div class="dashboard-heading mb-5">
        <h3 class="fs-22 font-weight-semi-bold">My Courses</h3>
    </div>
    <div class="dashboard-cards mb-5">
        @foreach ($aggregatedOrders as $order)
        <div class="card card-item card-item-list-layout">
            <div class="card-image">
                <a href="{{ route('user.course_details', $order->course->id) }}" class="d-block">
                    <img class="card-img-top" src="{{ Storage::url('upload/course/images/' . $order->course->image) }}" alt="Card image cap">
                </a>
                <div class="course-badge-labels">
                    @if ($order->course->best_seller == 1)
                        <div class="course-badge red">Bestseller</div>
                    @endif

                    @if ($order->course->highest_rated == 1)
                        <div class="course-badge green">Highest Rated</div>
                    @endif

                    @if ($order->course->featured == 1)
                        <div class="course-badge orange">Featured</div>
                    @endif

                    @if ($order->course->discount_price > 0)
                        <div class="course-badge blue">{{ round(($order->course->selling_price - $order->course->discount_price) / $order->course->selling_price * 100)}}%</div>
                    @else
                        <div class="course-badge blue">New</div>
                    @endif
                </div>
            </div><!-- end card-image -->
            <div class="card-body">
                <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $order->course->course_level }}</h6>
                <h5 class="card-title"><a href="course-details.html">{{ $order->course->name }}</a></h5>
                <p class="card-text"><a href="teacher-detail.html">{{ $order->course->instructor->name }}</a></p>
                <div class="rating-wrap d-flex align-items-center py-2">
                    <div class="review-stars">
                        <span class="rating-number">4.4</span>
                        <span class="la la-star"></span>
                        <span class="la la-star"></span>
                        <span class="la la-star"></span>
                        <span class="la la-star"></span>
                        <span class="la la-star-o"></span>
                    </div>
                    <span class="rating-total pl-1">(20,230)</span>
                </div><!-- end rating-wrap -->
                <ul class="card-duration d-flex align-items-center fs-15 pb-2">
                    <li class="mr-2">
                        <span class="text-black">Status:</span>
                        <span class="badge badge-success text-white">Published</span>
                    </li>
                    <li class="mr-2">
                        <span class="text-black">Duration:</span>
                        <span>{{ $order->course->duration }} hours</span>
                    </li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    @if ($order->course->discount_price > 0)
                        <p class="card-price text-black font-weight-bold">{{ $order->course->discount_price }} <span class="before-price font-weight-medium">{{ $order->course->selling_price }}</span></p>
                    @else 
                        <p class="card-price text-black font-weight-bold">{{ $order->course->selling_price }}</p>
                    @endif
                    <div class="card-action-wrap pl-3">
                        <a href="course-details.html" class="icon-element icon-element-sm shadow-sm cursor-pointer ml-1 text-success" data-toggle="tooltip" data-placement="top" data-title="View"><i class="la la-eye"></i></a>
                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer ml-1 text-secondary" data-toggle="tooltip" data-placement="top" data-title="Edit"><i class="la la-edit"></i></div>
                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer ml-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete">
                            <span data-toggle="modal" data-target="#itemDeleteModal" class="w-100 h-100 d-inline-block"><i class="la la-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->

        @endforeach

</div><!-- end container-fluid -->


@endsection