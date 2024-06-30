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
                    <li class="breadcrumb-item active" aria-current="page">Add Course</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('instructor.course_store') }}" method="POST" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Name</label>
                    <input type="text" class="form-control"  name="name" id="name" placeholder="Enter the course name">
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Title</label>
                    <input type="text" class="form-control"  name="title" id="title" placeholder="Enter the course title">
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Intro Video</label>
                    <input type="file" class="form-control @error('video_link') is-invalid @enderror"  name="video_link" id="video_link" accept="video/mp4 video/webm">
                    @error('video_link')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Category</label>
                    <select name="category_id" class="form-select mb-3 @error('category_id') is-invalid @enderror" id="category" aria-label="Default select example">
                        <option  selected="" disabled>Open this select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Subcategory</label>
                    <select name="sub_category_id" class="form-select mb-3 @error('sub_category_id') is-invalid @enderror" id="sub_category"  aria-label="Default select example">
                        <option  selected="" disabled>Open this select category</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Certificate Available</label>
                    <select name="certificate" class="form-select mb-3" aria-label="Default select example">
                        <option  selected="" disabled>Open this select category</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Level</label>
                    <select name="level" class="form-select mb-3" aria-label="Default select example">
                        <option  selected="" disabled>Open this select course level</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advance">Advance</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">Course Price</label>
                    <input type="text" class="form-control"  name="selling_price" id="selling_price">
                </div>

                <div class="col-md-4">
                    <label for="input1" class="form-label">Descount Price</label>
                    <input type="text" class="form-control"  name="discount_price" id="discount_price">
                </div>

                <div class="col-md-4">
                    <label for="input1" class="form-label">Duration</label>
                    <input type="text" class="form-control"  name="duration" id="duration">
                </div>

                <div class="col-md-4">
                    <label for="input1" class="form-label">Resources</label>
                    <input type="text" class="form-control"  name="resources" id="resources">
                </div>

                <div class="col-md-12">
                    <label for="input1" class="form-label">Course Prerequisites</label>
                    <textarea class="form-control" id="input11" placeholder="Prerequisites ..." name="prerequisites" rows="3"></textarea>
                </div>

                <div class="col-md-12">
                    <label for="input1" class="form-label">Course Description</label>
                    <textarea class="form-control" id="myeditorinstance" placeholder="Description ..." name="description"></textarea>
                </div>

                
                <div class="col-md-12">
                    <label for="fancy-file-upload" class="form-label">Course image</label>
                    <div class="image-upload-wrap" style="margin-top: 0;">
                        <input class="file-upload-input" type='file' name="image" onchange="readURL(this);" accept="image/*" />
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



                <p>Course Goals</p>
                
                
                <!--   //////////// Goal Option /////////////// -->

                <div class="row add_item">
                        
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="goals" class="form-label"> Goals </label>
                            <input type="text" name="course_goals[]" id="goals" class="form-control" placeholder="Goals ">
                        </div>
                    </div>
                    <div class="form-group col-md-6" style="padding-top: 30px;">
                        <a class="btn btn-success addeventmore"><i class="fa fa-plus-circle"></i> Add More..</a>
                    </div>
                </div> <!---end row-->



                <hr>
                <div class="row ms-lg-auto">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" style="border: 1px solid #00000063" type="checkbox" name="best_seller" value="1" id="best_seller">
                            <label class="form-check-label" for="best_seller">BestSaller</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" style="border: 1px solid #00000063" type="checkbox" name="featured" value="1" id="featured">
                            <label class="form-check-label" for="featured">Featured</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" style="border: 1px solid #00000063" type="checkbox" name="highest_rated" value="1" id="highest_rated">
                            <label class="form-check-label" for="highest_rated">Highest Rated</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 ms-auto me-auto" style="margin-top: 12px;">
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0">
                        <button type="submit" style="margin: 0; font-weight: bold;" class="btn btn-primary px-4 w-100">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!--========== Start of add multiple class with ajax ==============-->
<div style="visibility: hidden">
<div class="whole_extra_item_add" id="whole_extra_item_add">
    <div class="whole_extra_item_delete" id="whole_extra_item_delete">
        <div class="container mt-2">
            <div class="row">
            
            
            <div class="form-group col-md-6">
                <label for="goals">Goals</label>
                <input type="text" name="course_goals[]" id="goals" class="form-control" placeholder="Goals  ">
            </div>
            <div class="form-group col-md-6" style="padding-top: 20px">
                <span class="btn btn-success btn-sm addeventmore"><i class="fa fa-plus-circle"></i> Add</span>
                <span class="btn btn-danger btn-sm removeeventmore"><i class="fa fa-minus-circle"></i> Remove</span>
            </div>
            </div>
        </div>
    </div>
</div>
</div>      

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

<!----For Section-------->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var counter = 0;

    // Attach click event listener to the document
    document.addEventListener('click', function(event) {
        // Check if the clicked element has the class 'addeventmore'
        if (event.target.classList.contains('addeventmore')) {
            var whole_extra_item_add = document.getElementById('whole_extra_item_add').innerHTML;
            event.target.closest('.add_item').insertAdjacentHTML('beforeend', whole_extra_item_add);
            counter++;
        }
        // Check if the clicked element has the class 'removeeventmore'
        else if (event.target.classList.contains('removeeventmore')) {
            event.target.closest('#whole_extra_item_delete').remove();
            counter--;
        }
    });
});
</script>
<!--========== End of add multiple class with ajax ==============-->

<script>
    const selectElement = document.getElementById('category');

    selectElement.addEventListener('change', (event) => {
        const selectedValue = event.target.value;

        const subCategoriesSelect = document.getElementById('sub_category');
        while (subCategoriesSelect.options.length > 1) {
                subCategoriesSelect.remove(1);
            }
        const url = "{{ route('instructor.get_subCategories', ':id') }}".replace(':id', selectedValue);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            
            data.forEach(element => {

                var option = document.createElement("option");
                option.text = element.subCategory_name;
                option.value = element.id;
                subCategoriesSelect.add(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
        });
</script>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>

    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                name: {
                    required : true,
                }, 
                title: {
                    required : true,
                }, 
                price: {
                    required : true,
                }, 
                duration: {
                    required : true,
                }, 
                image: {
                    required : true,
                }, 
            },
            messages :{
                name: {
                    required : 'Please Enter Course Name',
                }, 
                title: {
                    required : 'Please Enter Course Title',
                }, 
                price: {
                    required : 'Please Enter Course Price',
                }, 
                duration: {
                    required : 'Please Enter Course Duration',
                },
                image: {
                    required : 'Please Enter Course Image',
                },


            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>
@endsection