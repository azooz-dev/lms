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
                    <li class="breadcrumb-item active" aria-current="page">All Categories</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('category.add')
                <div class="btn-group">
                    <a href="{{ route('admin.add_category') }}" class="btn btn-primary">Add Category</a>
                </div>
            @endcan
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Categories</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Category Image</th>
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center"><img src="{{ Storage::url('upload/category_images/' . $category->image) }}" alt="" width="100" height="50"></td>
                            <td class="text-center">{{ $category->category_name  }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    @can('category.edit')
                                        <a href="{{ route('admin.edit_category', $category->id) }}" class="btn btn-info" style="margin-right: 10px; width: 100px">Edit</a>
                                    @endcan
                                    @can('category.delete')
                                        <form action="{{ route('admin.category_destory', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" id="delete" class="btn btn-danger" style="width: 100px">Delete</button>
                                        </form>
                                    @endcan
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