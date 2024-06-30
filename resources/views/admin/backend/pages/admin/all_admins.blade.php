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
                    <li class="breadcrumb-item active" aria-current="page">All Admin</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('admin.add_admin') }}" class="btn btn-primary">Add Admin</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Admin</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Image Profile</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $key => $admin)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center"><img src="{{ (!empty($admin->photo)) ? Storage::url('public/upload/admin_images/'. $admin->photo) : asset('storage/upload/images.jpg') }}" alt="" width="100" height="50"></td>
                            <td class="text-center">{{ $admin->name  }}</td>
                            <td class="text-center">{{ $admin->email  }}</td>
                            <td class="text-center">{{ $admin->phone  }}</td>
                            <td class="text-center">{{ $admin->address  }}</td>
                            <td class="text-center">
                                @foreach ($admin->roles as $role)
                                    <span class="badge badge-pill bg-danger">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.edit_admin', $admin->id) }}" class="btn btn-info" style="margin-right: 10px; width: 100px">Edit</a>
                                    <form action="{{ route('admin.admin_destory', $admin->id) }}" method="POST">
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