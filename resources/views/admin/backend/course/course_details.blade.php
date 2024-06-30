@extends('admin.dashboard')

@section('content')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Course Detials</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Course Detials</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="{{ Storage::url('upload/course/images/' .  $course->image) }}" class="rounded-circle p-1 border" width="90" height="90" alt="...">
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mt-0">{{ $course->name }}</h5>
                                <p class="mb-0">{{ $course->title }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th><strong>Category : </strong></th>
                                        <td>{{ $course->category->category_name }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Subcategory : </strong></th>
                                        <td>{{ $course->subCategory->subCategory_name }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Instructor : </strong></th>
                                        <td>{{ $course->instructor->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Level : </strong></th>
                                        <td><span class="badge bg-primary">{{ $course->course_level }}</span></td>
                                    </tr>
                                    <tr>
                                        <th><strong>Duration : </strong></th>
                                        <td>{{ $course->duration }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Video : </strong></th>
                                        <td><video width="300" height="200" controls>
                                                <source src="{{ Storage::url('upload/course/videos/'. $course->video_link) }}">
                                            </video>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th><strong>Resources : </strong></th>
                                        <td>{{ $course->resources }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Certificate : </strong></th>
                                        <td>{{ $course->certificate }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Price : </strong></th>
                                        <td>${{ $course->selling_price }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Discount Price : </strong></th>
                                        <td>${{ $course->discount_price }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Status : </strong></th>
                                        <td>@if ($course->status == 1)
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection