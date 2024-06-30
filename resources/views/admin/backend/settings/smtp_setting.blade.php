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
                    <li class="breadcrumb-item active" aria-current="page">Smtp Setting</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Smtp Setting</h5>
            <form action="{{ route('admin.update_smtp', $smtp->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label for="input1" class="form-label">Mailer</label>
                    <input type="text" class="form-control @error('mailer') is-invalid @enderror"  name="mailer" id="mailer" value="{{ $smtp->mailer }}">
                    @error('mailer')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Host</label>
                    <input type="text" class="form-control @error('host') is-invalid @enderror"  name="host" id="host" value="{{ $smtp->host }}">
                    @error('host')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Port</label>
                    <input type="text" class="form-control @error('port') is-invalid @enderror"  name="port" id="port" value="{{ $smtp->port }}">
                    @error('port')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"  name="username" id="username" value="{{ $smtp->username }}">
                    @error('username')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"  name="password" id="password" value="{{ $smtp->password }}">
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Encryption</label>
                    <input type="text" class="form-control @error('encryption') is-invalid @enderror"  name="encryption" id="encryption" value="{{ $smtp->encryption }}">
                    @error('encryption')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">From Address</label>
                    <input type="text" class="form-control @error('from_address') is-invalid @enderror"  name="from_address" id="from_address" value="{{ $smtp->from_address }}">
                    @error('from_address')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                </div>

                <div class="col-md-6" style="margin-top: 15px;">
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0">
                        <button type="submit" style="margin: 0" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

@endsection