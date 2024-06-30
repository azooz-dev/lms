@extends('admin.dashboard')


@section('content')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="px-sm-0 px-lg-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Roles In Permissions</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('admin.add_role_permissions') }}" class="btn btn-primary">Add Role In Permission</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Roles In Permissions</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Role Name</th>
                            <th class="text-center">Permissions</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ $role->name }}</td>
                            <td class="text-center">
                                @foreach ($role->permissions as $permission)
                                    <span class="badge bg-danger">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.edit_role_permissions', $role->id) }}" class="btn btn-info" style="margin-right: 10px; width: 100px">Edit</a>
                                    <form action="{{ route('admin.role_permissions_destory', $role->id) }}" method="POST">
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