@extends('instructor.dashboard')

@section('content')
@php
    $totalPrice = 0;
@endphp

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Order Details</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Name : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ $payment->name }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ $payment->email }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Phone : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ $payment->phone }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ $payment->address }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Payment Type : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ $payment->payment_type }}</strong>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Total Amount : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">${{ $payment->total_amount }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Cash Delivery : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ $payment->cash_delivery }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Invoice Number : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ $payment->invoice_number }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Order Date : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <strong style="font-size: 13px;">{{ \Carbon\Carbon::parse($payment->created_at)->format('j F Y') }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Status : </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        @if ($payment->status == 'Pending')
                                            <span class="badge bg-success" style="font-size: 12px;">Pending</span>
                                            @else
                                            <span class="badge bg-success" style="font-size: 12px;">Confirmed</span>
                                        @endif
                                    </div>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>



            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">S/N</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Course Name</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Subcategory</th>
                                    <th class="text-center">Instructor</th>
                                    <th class="text-center">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment->orders as $key => $order)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center"><img src="{{ Storage::url('public/upload/course/images/' . $order->course->image) }}" alt="" width="100" height="50"></td>
                                        <td class="text-center">{{ $order->course->name }}</td>
                                        <td class="text-center">{{ $order->course->category->category_name  }}</td>
                                        <td class="text-center">{{ $order->course->subCategory->subCategory_name  }}</td>
                                        <td class="text-center">{{ $order->course->instructor->name  }}</td>
                                        <td class="text-center">@if ($order->course->discount_price > 0)
                                            ${{ $order->course->discount_price }}
                                            @php
                                                $totalPrice += $order->course->discount_price;
                                            @endphp
                                            @else
                                            ${{ $order->course->selling_price }}
                                            @php
                                                $totalPrice += $order->course->selling_price;
                                            @endphp`
                                        @endif</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6"></td>
                                    <td><strong>Total Price: ${{ $totalPrice }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection