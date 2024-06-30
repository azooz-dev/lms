@extends('admin.dashboard')


@section('content')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Pending Orders</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Pending Orders</h6>
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
                        @foreach ($payments as $key => $payment)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($payment->created_at)->format('j F Y') }}</td>
                            <td class="text-center">{{ $payment->invoice_number  }}</td>
                            <td class="text-center">{{ $payment->total_amount  }}</td>
                            <td class="text-center">{{ $payment->payment_type  }}</td>
                            <td class="text-center"><span class="badge rounded--pill bg-success">{{ $payment->status }}</span></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.order_details', $payment->id) }}" class="btn btn-info">Details</a>
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