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
                    <li class="breadcrumb-item active" aria-current="page">Edit Subcategory</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit subcategory</h5>
            <form action="{{ route('admin.update_subCategory', $subCategory->id) }}" method="POST" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label for="input1" class="form-label">subcategory Name</label>
                    <input type="text" class="form-control @error('subCategory_name') is-invalid @enderror"  name="subCategory_name" id="subCategory_name" value="{{ $subCategory->subCategory_name }}" placeholder="Enter the subcategory name">
                    @error('subCategory_name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="input1" class="form-label">Category Name</label>
                    <select name="category_id" class="form-select mb-3" aria-label="Default select example">
                        <option  selected="" disabled>Open this select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $subCategory->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6" style="margin-top: 0;">
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