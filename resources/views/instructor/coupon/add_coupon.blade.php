@extends('instructor.dashboard')

@section('content')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Coupon</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Add Coupon</h5>
            <form action="{{ route('instructor.coupon_store', Auth::user()->id) }}" method="POST" id="myForm" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label for="input1" class="form-label">Coupon Name</label>
                    <input type="text" class="form-control @error('coupon_name') is-invalid @enderror"  name="coupon_name" placeholder="Enter the coupon name">
                    @error('coupon_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Coupon Discount</label>
                    <input type="text" class="form-control @error('coupon_discount') is-invalid @enderror"  name="coupon_discount" placeholder="Enter the discount amount %">
                    @error('coupon_discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Coupon Validity</label>
                    <input type="date" class="form-control @error('coupon_validity') is-invalid @enderror"  name="coupon_validity" min="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                    @error('coupon_validity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Name</label>
                    <select class="form-select mb-3 @error('course_id') is-invalid @enderror" name="course_id">
                        <option selected="">Open this select menu</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6" style="margin-top: 0">
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0">
                        <button type="submit" style="margin: 0" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

@endsection