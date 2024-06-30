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
                    <li class="breadcrumb-item active" aria-current="page">All Instructors</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Instructors</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($instructors as $key => $instructor)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td class="text-center">{{ $instructor->name }}</td>
                                <td class="text-center">{{ $instructor->username }}</td>
                                <td class="text-center">{{ $instructor->email }}</td>
                                <td class="text-center">{{ $instructor->phone }}</td>
                                <td class="text-center">{{ $instructor->address }}</td>
                                <td class="text-center">@if ($instructor->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif</td>
                                <td class="text-center"><div class="form-check-danger form-check form-switch">
                                    <input class="form-check-input" style="font-size: 17px; margin-left: -20px;" type="checkbox" id="statusSwitch{{ $instructor->id }}" 
                                    @if ($instructor->status == 1) checked @endif 
                                    data-id="{{ $instructor->id }}" 
                                    data-name="{{ $instructor->name }}" 
                                    onchange="updateInstructorStatus(this)">
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
    function updateInstructorStatus(checkbox) {
    const instructorId = checkbox.getAttribute('data-id');
    const instructorName = checkbox.getAttribute('data-name');
    const isChecked = checkbox.checked;
    const status = isChecked ? 1 : 0;

    // Construct the URL using JavaScript
    const url = '{{ route('admin.update_instructor_status', ':id') }}'.replace(':id', instructorId);

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
            toastr.success(`${instructorName} has been successfully activated.`, 'Success');
        } else {
            // Handle error, e.g., show a message to the user
            toastr.error('Failed to update instructor status.', 'Error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle error, e.g., revert the checkbox state
        checkbox.checked = !isChecked;
        // Display an error toast message
        toastr.error('An error occurred while updating the instructor status.', 'Error');
    });
}
</script>
@endsection