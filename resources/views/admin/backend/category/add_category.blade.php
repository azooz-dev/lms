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
                    <li class="breadcrumb-item active" aria-current="page">Add Category</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Add Category</h5>
            <form action="{{ route('admin.category_store') }}" method="POST" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <label for="input1" class="form-label">Category Name</label>
                    <input type="text" class="form-control @error('category_name') is-invalid @enderror"  name="category_name" id="category_name" placeholder="Enter the category name">
                    @error('category_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                </div>
                <div class="col-md-6" style="margin-bottom: 30px;">
                    <label for="fancy-file-upload" class="form-label">Category image</label>
                    <div class="image-upload-wrap">
                        <input class="file-upload-input @error('image') is-invalid @enderror" type='file' name="image" onchange="readURL(this);" accept="image/*" />
                        <div class="drag-text">
                            <h3>Drag and select add Image</h3>
                        </div>
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="file-upload-content">
                        <img class="file-upload-image" src="#" alt="your image" />
                        <div class="image-title-wrap">
                            <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                </div>
                <div class="col-md-6" style="margin-top: -20px;">
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0">
                        <button type="submit" style="margin: 0" class="btn btn-primary px-4">Save</button>
                    </div>
            </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
    function removeUpload() {
	$('.file-upload-input').replaceWith($('.file-upload-input').clone());
	$('.file-upload-content').hide();
	$('.image-upload-wrap').show();
	}
	$('.image-upload-wrap').bind('dragover', function () {
		$('.image-upload-wrap').addClass('image-dropping');
	});
	$('.image-upload-wrap').bind('dragleave', function () {
		$('.image-upload-wrap').removeClass('image-dropping');
	});
    

    function readURL(input, isEditing = false, currentImageUrl = '') {
    if (input && input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.image-upload-wrap').hide();

            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();

            $('.image-title').html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);
    } else if (isEditing && currentImageUrl) {
        $('.image-upload-wrap').hide();
        $('.file-upload-image').attr('src', currentImageUrl);
        $('.file-upload-content').show();
        $('.image-title').html(currentImageUrl.split('/').pop());
    } else {
        removeUpload();
    }
    
}
</script>

@endsection