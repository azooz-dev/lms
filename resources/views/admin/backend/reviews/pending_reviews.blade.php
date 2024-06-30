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
                    <li class="breadcrumb-item active" aria-current="page">All Pending Reviews</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Pending Reviews</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Course Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Comment</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $key => $review)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td class="text-center">{{ $review->course->name }}</td>
                                <td class="text-center">{{ $review->user->username }}</td>
                                <td class="text-center">{{ $review->message }}</td>
                                <td class="text-center">
                                    @if ($review->rating == 0)
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                    @elseif ($review->rating == 1)
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        @elseif ($review->rating == 2)
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        @elseif ($review->rating == 3)
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        @elseif ($review->rating == 4)
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-secondary"></i>
                                        @elseif ($review->rating == 5)
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                        <i class="bx bxs-star text-warning"></i>
                                    @endif
                                </td>
                                <td class="text-center"><div class="form-check-danger form-check form-switch">
                                    <input class="form-check-input" style="font-size: 17px; margin-left: -20px;" type="checkbox" id="statusSwitch{{ $review->id }}" 
                                    @if ($review->status == '1') checked @endif 
                                    data-id="{{ $review->id }}" 
                                    onchange="updateReviewStatus(this)">
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
    function updateReviewStatus(checkbox) {
    const reviewId = checkbox.getAttribute('data-id');
    const isChecked = checkbox.checked;
    const status = isChecked ? 1 : 0;

    // Construct the URL using JavaScript
    const url = '{{ route('admin.update_review_status', ':id') }}'.replace(':id', reviewId);

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
            
            toastr.success(`Review has been successfully activated.`, 'Success');
        } else {
            // Handle error, e.g., show a message to the user
            toastr.error('Failed to update review status.', 'Error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle error, e.g., revert the checkbox state
        checkbox.checked = !isChecked;
        // Display an error toast message
        toastr.error('An error occurred while updating the review.', 'Error');
    });
}
</script>
@endsection