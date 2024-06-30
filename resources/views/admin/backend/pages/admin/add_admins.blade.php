@extends('admin.dashboard')

@section('content')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Admin</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Add Admin</h5>
            <form action="{{ route('admin.admin_store') }}" method="POST" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <label for="input1" class="form-label">Admin Name :</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"  name="name" id="name" placeholder="Enter the admin name">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Admin User name :</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"  name="username" id="username" placeholder="Enter the admin username">
                    @error('username')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Admin Email :</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"  name="email" id="email" placeholder="Enter the admin email">
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Admin Phone :</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"  name="phone" id="phone" placeholder="Enter the admin phone">
                    @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Admin Password :</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"  name="password" id="password" placeholder="Enter the admin password">
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Password Confirmation :</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"  name="password_confirmation" id="password" placeholder="Enter the password confirmation">
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Admin Address :</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"  name="address" id="address" placeholder="Enter the admin address">
                    @error('address')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Role Name</label>
                    <select name="role" class="form-select mb-3" aria-label="Default select example">
                        <option  selected="" disabled>Open this select role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6" style="margin: 0;">
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0;">
                        <button type="submit" style="margin: 0;" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

@endsection