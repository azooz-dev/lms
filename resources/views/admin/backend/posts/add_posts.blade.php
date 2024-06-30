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
                    <li class="breadcrumb-item active" aria-current="page">Add Post</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Add Post</h5>
            <form action="{{ route('admin.post_store', Auth::user()->id) }}" method="POST" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <label for="input1" class="form-label">Post Title :</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" id="title" placeholder="Enter the post title">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Blog Category :</label>
                    <select name="category_id" class="form-select mb-3" aria-label="Default select example">
                        <option  selected="" disabled>Open this select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label for="input1" class="form-label">Tags :</label>
                    <input type="text" class="form-control" name="tag" data-role="tagsinput" value="example1, example2">
                </div>

                <div class="col-md-12">
                        <label for="input1" class="form-label">Description :</label>
                        <textarea class="form-control" id="myeditorinstance" placeholder="Description ..." name="description"></textarea>
                </div>

                

                <div class="col-md-12" style="margin-bottom: 30px;">
                    <label for="fancy-file-upload" class="form-label">Post image :</label>
                    <div class="image-upload-wrap" style="margin-top: 0px;">
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
                <div class="col-md-6" style="margin-top: -10px;">
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0">
                        <button type="submit" style="margin: 0; width: 150px;" class="btn btn-primary px-4">Save</button>
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



<!-- At the end of your add_course.blade.php file -->
<script src="https://cdn.tiny.cloud/1/soqkybk1yse08bhw70fw8q97x6l3pdkndwgsnphim44zcfm8/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({
        selector: 'textarea#myeditorinstance',
        height: 300,
        plugins: 'code table lists',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
        // Additional configuration options...
    });
</script>

@endsection