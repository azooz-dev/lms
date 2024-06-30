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
                    <li class="breadcrumb-item active" aria-current="page">Site Setting</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Site Setting</h5>
            <form action="{{ route('admin.update_site_setting', $site->id) }}" method="POST" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="input1" class="form-label">Address :</label>
                    <input type="text" class="form-control @error('address_site') is-invalid @enderror"  name="address_site" id="address_site" value="{{ $site->address_site }}" placeholder="Enter the address site">
                    @error('address_site')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Email :</label>
                    <input type="text" class="form-control @error('email_site') is-invalid @enderror"  name="email_site" id="email_site" value="{{ $site->email_site }}" placeholder="Enter the email site">
                    @error('email_site')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Phone :</label>
                    <input type="text" class="form-control @error('phone_site') is-invalid @enderror"  name="phone_site" id="phone_site" value="{{ $site->phone_site }}" placeholder="Enter the phone site">
                    @error('phone_site')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Facebook :</label>
                    <input type="text" class="form-control @error('facebook') is-invalid @enderror"  name="facebook" id="facebook" value="{{ $site->facebook }}" placeholder="Enter the facebook link">
                    @error('facebook')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Instagram :</label>
                    <input type="text" class="form-control @error('instagram') is-invalid @enderror"  name="instagram" id="instagram" value="{{ $site->instagram }}" placeholder="Enter the instagram link">
                    @error('instagram')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Twitter :</label>
                    <input type="text" class="form-control @error('twitter') is-invalid @enderror"  name="twitter" id="twitter" value="{{ $site->twitter }}" placeholder="Enter the twitter link">
                    @error('twitter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Linkedin :</label>
                    <input type="text" class="form-control @error('linkedin') is-invalid @enderror"  name="linkedin" id="linkedin" value="{{ $site->linkedin }}" placeholder="Enter the linkedin link">
                    @error('linkedin')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Copyright :</label>
                    <input type="text" class="form-control @error('copyright') is-invalid @enderror"  name="copyright" id="copyright" value="{{ $site->copyright }}" placeholder="Enter the copyright">
                    @error('copyright')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6" style="margin-bottom: 30px;">
                    <input type="hidden" id="currentImageUrl"  value="{{ Storage::url('upload/logo/' . $site->logo) }}">
                    <label for="fancy-file-upload" class="form-label">Logo Site :</label>
                    <div class="image-upload-wrap">
                        <input class="file-upload-input" type='file' name="logo" onchange="readURL(this, true);" accept="image/*" />
                        <div class="drag-text">
                            <h3>Drag and select add Image</h3>
                        </div>
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
                <div class="col-md-6" style="margin-top: -10px;">
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0">
                        <button type="submit" style="margin: 0" class="btn btn-primary px-4">Save Changes</button>
                    </div>
            </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var currentImageUrl = $('#currentImageUrl').val();
        if (currentImageUrl) {
            readURL(null, true, currentImageUrl); // Assuming you modify readURL to accept the currentImageUrl as a parameter
        }
    });


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