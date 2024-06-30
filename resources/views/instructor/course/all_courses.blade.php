@extends('instructor.dashboard')

@section('content')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Courses</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('instructor.add_course') }}" class="btn btn-primary" style="width: 150px;">Add Course</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Course Name</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Discount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $key => $course)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center"><img src="{{ Storage::url('upload/course/images/' . $course->image) }}" alt="" width="100" height="50"></td>
                            <td class="text-center">{{ $course->name }}</td>
                            <td class="text-center">{{ $course->category->category_name }}</td>
                            <td class="text-center">{{ $course->selling_price }}</td>
                            <td class="text-center">{{ $course->discount_price }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('instructor.edit_course', $course->id) }}" class="btn btn-info" title="Edit" style="margin-right: 5px; "><i class="lni lni-eraser"></i></a>
                                    <form action="{{ route('instructor.course_destory', $course->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="delete" class="btn btn-danger" title="Delete"><i class="lni lni-trash"></i></button>
                                    </form>
                                    <a href="{{ route('instructor.create_section', $course->id) }}" class="btn btn-warning" style="margin-left: 5px;"><i class="lni lni-list" title="Lectures"></i></a>
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