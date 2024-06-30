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
                    <li class="breadcrumb-item active" aria-current="page">All Courses</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Courses</h6>
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
                            <th class="text-center">Instructor</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $key => $course)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td class="text-center"><img src="{{ Storage::url('upload/course/images/' . $course->image) }}" alt="" width="70px" height="40px"></td>
                                <td class="text-center">{{ $course->name }}</td>
                                <td class="text-center">{{ $course->category->category_name }}</td>
                                <td class="text-center">{{ $course->instructor->name }}</td>
                                <td class="text-center"><a href="{{ route('admin.course_details', $course->id) }}" class="btn btn-info" title="Course Detials"><i class="fas fa-info-circle"></i></a></td>
                                <td class="text-center"><div class="form-check-danger form-check form-switch">
                                    <input class="form-check-input" style="font-size: 17px; margin-left: -20px;" type="checkbox" id="statusSwitch{{ $course->id }}" 
                                    @if ($course->status == 1) checked @endif 
                                    data-id="{{ $course->id }}"  
                                    onchange="updateCourseStatus(this)">
                                </div></td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function updateCourseStatus(checkbox) {
    const courseId = checkbox.getAttribute('data-id');
    const isChecked = checkbox.checked;
    const status = isChecked ? 1 : 0;

    // Construct the URL using JavaScript
    const url = '{{ route('admin.update_course_status', ':id') }}'.replace(':id', courseId);

    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Display a success toast message with the instructor's name
            toastr.success(`The course has been successfully activated.`, 'Success');
        } else {
            // Handle error, e.g., show a message to the user
            toastr.error('Failed to update course status.', 'Error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle error, e.g., revert the checkbox state
        checkbox.checked = !isChecked;
        // Display an error toast message
        toastr.error('An error occurred while updating the course status.', 'Error');
    });
}
</script>
@endsection