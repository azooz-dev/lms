@extends('instructor.dashboard')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@section('content')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Orders</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Invoice</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Payment</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aggregatedOrders as $key => $order)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($order->payment->created_at)->format('j F Y') }}</td>
                            <td class="text-center">{{ $order->payment->invoice_number }}</td>
                            <td class="text-center">${{ $order->payment->total_amount }}</td>
                            <td class="text-center">{{ $order->payment->payment_type }}</td>
                            <td class="text-center"><span class="badge bg-success">{{ $order->payment->status }}</span></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('instructor.order_details', $order->payment->id) }}" class="btn btn-info" title="Information" style="margin-right: 10px; margin-bottom: 10px; padding: 6px 12px; "><i class="fas fa-list" style="font-size: 20px;"></i></a>
                                    <a href="{{ route('instructor.invoice_download', $order->payment->id) }}" class="btn btn-danger" style="margin-right: 10px; padding: 6px 12px; margin-bottom: 10px;" title="Download"><i class="fa-solid fa-download" style="font-size: 20px;"></i></a>
                                </div>
                            </td>
                        </tr>
                            
                        @endforeach
                        

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection