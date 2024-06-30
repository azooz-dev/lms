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
            <div class="btn-group">
                <a href="{{ route('admin.add_subCategory') }}" class="btn btn-primary">Add Subcategory</a>
            </div>
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
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Subcategory Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subCategories as $key => $subCategory)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ $subCategory->category->category_name }}</td>
                            <td class="text-center">{{ $subCategory->subCategory_name  }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.edit_subCategory', $subCategory->id) }}" class="btn btn-info" style="margin-right: 10px; width: 100px">Edit</a>
                                    <form action="{{ route('admin.subCategory_destory', $subCategory->id) }}" method="POST">
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