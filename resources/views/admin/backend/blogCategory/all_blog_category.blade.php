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
                    <li class="breadcrumb-item active" aria-current="page">Blog Categories</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <button class="btn btn-primary" id="add-btn" data-bs-toggle="modal" data-bs-target="#create-category-modal">Add Blog Category</button>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Blog Categories</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Category Slug</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ $category->category_name }}</td>
                            <td class="text-center">{{ $category->category_slug  }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    {{-- <a href="{{ route('admin.edit_subCategory', $category->id) }}" class="btn btn-info" style="margin-right: 10px; width: 100px">Edit</a> --}}
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" style="margin-right: 5px; width: 150px;" data-bs-target="#create-category-modal" data-action="edit" data-post="{{ json_encode($category->id) }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.blog_category_destory', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="delete" class="btn btn-danger" style="width: 150px">Delete</button>
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


<!-- Start Model -->
{{-- {{ route('posts.update', ['post' => 'CATEGORY_ID_PLACEHOLDER']) }} --}}

<div class="modal fade" id="create-category-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create A New Blog Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="post-form" action="{{ route('admin.update_blog_category', ['id' => 'CATEGORY_ID_PLACEHOLDER']) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Blog Category Name</label>
                        <input type="text" id="category_name" name="category_name" class="form-control" id="post-title-input">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="post-form">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<!-- End Model -->


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createPostModal = document.getElementById('create-category-modal');
        var postForm = document.getElementById('post-form');
        var modalTitle = document.getElementById('exampleModalLabel');
    
        createPostModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var action = button.getAttribute('data-action'); // Extract info from data-* attributes
            var postJson = button.getAttribute('data-post'); // Extract info from data-* attributes

            let post = JSON.parse(postJson);
            
            
            // Set the form's action URL and change the modal title based on the action
            // console.log(JSON.parse(post));
            if (action === 'edit') {
                const url = `{{ route('admin.blog_category_edit', ':id') }}`.replace(':id', JSON.parse(post));

                
                postForm.action = postForm.action.replace('CATEGORY_ID_PLACEHOLDER', JSON.parse(post));
                modalTitle.textContent = 'Edit Blog Category';
                postForm.querySelector('button[type="submit"]').textContent = 'Edit';
                //Append @method('PUT') to the form
                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                postForm.appendChild(methodField);
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    postForm.querySelector('input[name="category_name"]').value = data.category.category_name;
                })
            } else {
                postForm.action = '{{ route('admin.store_blog_category') }}';
                modalTitle.textContent = 'Create A New Blog Category';
                postForm.querySelector('button[type="submit"]').textContent = 'Create';
                postForm.querySelector('input[name="category_name"]').value = '';
                // Remove @method('PUT') from the form if it exists
                var existingMethodField = postForm.querySelector('input[name="_method"]');
                if (existingMethodField) {
                    postForm.removeChild(existingMethodField);
                }
            }
        });
    });
</script>

@endsection