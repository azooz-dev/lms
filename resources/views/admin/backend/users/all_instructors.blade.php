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
                    <li class="breadcrumb-item active" aria-current="page">All Instructors</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Instructors</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($instructors as $key => $instructor)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td class="text-center"><img src="{{ (!empty($instructor->photo)) ? Storage::url('public/upload/admin_images/'. $instructor->photo) : asset('storage/upload/images.jpg') }}" alt="" width="50" height="50"></td>
                                <td class="text-center">{{ $instructor->name }}</td>
                                <td class="text-center">{{ $instructor->username }}</td>
                                <td class="text-center">{{ $instructor->email }}</td>
                                <td class="text-center">{{ $instructor->phone }}</td>
                                <td class="text-center">{{ $instructor->address }}</td>
                                <td class="text-center">
                                    @if ($instructor->userOnline())
                                        <span class="badge badge-pill bg-success">Active Now</span>
                                        @else
                                        <span class="badge badge-pill bg-danger">{{ Carbon\Carbon::parse($instructor->last_seen)->diffForHumans() }}</span>

                                @endif</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection