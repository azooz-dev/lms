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
                    <li class="breadcrumb-item active" aria-current="page">Edit Lecture</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('instructor.create_section', $lecture->course_id) }}" class="btn btn-primary" style="width: 150px;">Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Lecture</h5>
            <form action="{{ route('instructor.update_lecture', $lecture->id) }}" method="POST" id="myForm" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label for="input1" class="form-label">Lecture Title</label>
                    <input type="text" class="form-control"  name="lecture_title" id="l ecture_title" value="{{ $lecture->lecture_title }}">
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Video Link</label>
                    <input type="text" class="form-control"  name="url" id="url" value="{{ $lecture->url }}">
                </div>

                <div class="col-md-12">
                    <label for="input1" class="form-label">Lecture Content</label>
                    <textarea class="form-control" name="content" id="content" >{{ $lecture->content }}</textarea>
                </div>

                <div class="col-md-6 mt-2">
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