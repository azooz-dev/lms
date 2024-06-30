@extends('instructor.dashboard')


@section('content')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Coupons</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('instructor.add_coupon', Auth::user()->id) }}" class="btn btn-primary">Add Coupon</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Coupons</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Coupon Name</th>
                            <th class="text-center">Coupon Discount</th>
                            <th class="text-center">Coupon Validity</th>
                            <th class="text-center">Course Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $key => $coupon)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ $coupon->coupon_name }}</td>
                            <td class="text-center">{{ $coupon->coupon_discount  }}%</td>
                            <td class="text-center">{{ Carbon\Carbon::parse($coupon->coupon_validity)->format('D d F Y')  }}</td>
                            <td class="text-center">{{ $coupon->course->name  }}</td>
                            <td class="text-center">
                                @if ($coupon->coupon_validity >= Carbon\Carbon::now())
                                    <span class="badge bg-success">Valid</span>
                                    @else
                                    <span class="badge bg-danger">Invalid</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('instructor.edit_coupon', $coupon->id) }}" class="btn btn-info" style="margin-right: 10px; width: 100px">Edit</a>
                                    <form action="{{ route('instructor.destory_coupon', $coupon->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="delete" class="btn btn-danger" style="width: 100px">Delete</button>
                                    </form>
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