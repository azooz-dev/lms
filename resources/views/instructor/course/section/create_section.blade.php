@extends('instructor.dashboard')

@section('content')

<div class="page-content">
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ Storage::url('upload/course/images/' . $course->image) }}" class="rounded-circle p-1 border" width="90" height="90" alt="...">
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mt-0">{{ $course->name }}</h5>
                            <p class="mb-0">{{ $course->title }}</p>
                        </div>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Section</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    @foreach ($course->sections as $key => $section)
                    <div class="card">
                        <div class="card-body p-4 d-flex justify-content-between">
                            <h5 style="font-size: 1rem;" class="mt-2">{{ $section->section_title }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('instructor.section_destory', $section->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Section</button>
                                </form>
                                <a href="#" class="btn btn-primary ms-1" onclick="addLectureDiv({{ $course->id }}, {{ $section->id }}, 'lectureContainer{{ $key }}', {{ $key }}) " id="addLectureBtn($key)">Add Lecture</a>
                            </div>
                        </div>
                        <div class="courseHide" id="lectureContainer{{ $key }}">
                            <div class="container" id="lectures{{ $key }}">
                                @foreach ($section->lectures as $lecture)
                                <div class="lectureDiv{{ $key }} mb-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $loop->iteration }}. {{ $lecture->lecture_title }}</strong>
                                    </div>

                                    <div class="btn-group">
                                        <a href="{{ route('instructor.edit_lecture', $lecture->id) }}" class="btn btn-sm btn-primary">Edit</a> &nbsp;
                                        <form action="{{ route('instructor.lecture_destory', $lecture->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" id="delete" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



<div class="row row-cols-auto g-3">
    <div class="col">
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('instructor.section_store', $course->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Section Title:</label>
                                <input type="text" class="form-control" name="section_title" id="recipient-name">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->

<script>
    function addLectureDiv(courseId, sectionId, containerId, key) {
        const lectureContainer = document.getElementById(containerId);
        const newLectureDiv = document.createElement('div');

        newLectureDiv.classList.add('addLectureDiv' + key, 'mb-3');
        let id = Math.random().toString(36).substr(2, 9);
        newLectureDiv.setAttribute('id', id);
        newLectureDiv.innerHTML = `<div class="container">
            <label>Lecture Title</label>
            <input type="text" class="form-control mb-2" name="lecture_title" placeholder="Enter the lecture title">
            <textarea class="form-control mb-2" name="content" placeholder="Enter the lecture content"></textarea>

            <label>Lecture Video Link</label>
            <input type="text" class="form-control" name="url" placeholder="Enter the video link">

            <button type="" class="btn btn-primary mt-3" onclick="saveLecture('${sectionId}', '${containerId}', '${id}', '${key}')">Save Lecture</button>
            <button type="" class="btn btn-secondary mt-3" onclick="hideLectureContainer('${id}')">Cansel</button>
            </div>
        `;

        lectureContainer.appendChild(newLectureDiv);

    }


    function hideLectureContainer(id) {
        const lectureContainer = document.getElementById(id).remove();
    }


    function saveLecture(sectionId, containerId, id, key) {

    const lectureContainer = document.getElementById(containerId);
    const lectureTitle = lectureContainer.querySelector('input[name="lecture_title"]').value.trim();
    const content = lectureContainer.querySelector('textarea[name="content"]').value.trim();
    const url_video = lectureContainer.querySelector('input[name="url"]').value.trim();

    const errorMessage = [];

    if (lectureTitle === '') {
        errorMessage.push('Lecture Title');
    }
    if (url_video === '') {
        errorMessage.push('Video Link');
    }

    if (errorMessage.length > 0) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 6000,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        Toast.fire({
            icon: 'error',
            title: errorMessage.join(', ') + ' fields are empty'
        });

    } else {
        let dataString = JSON.stringify({
                'lecture_title': lectureTitle,
                'content': content,
                'url': url_video,
            });

        const url = '{{ route('instructor.lecture_store', ':sectionId') }}'.replace(':sectionId', sectionId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                'lecture_title': lectureTitle,
                'content': content,
                'url': url_video,
            })
        })
        .then(response => response.json())
        .then(data => {
            const lectureDiv = document.querySelectorAll('.lectureDiv' + key).length;
            const editUrl = `/instructor/lecture/${data.data.id}/edit`; // Construct the URL using the lecture ID
            const deleteUrl = `/instructor//lecture/delete/${data.data.id}`; // Construct the URL using the lecture ID
            const lecture = `<div class="lectureDiv mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <strong>${lectureDiv + 1}. ${data.data.lecture_title}</strong>
                </div>

                <div class="btn-group">
                    <a href="${editUrl}" class="btn btn-sm btn-primary">Edit</a> &nbsp;
                    <form action="${deleteUrl}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" id="delete">Delete</button>
                    </form>
                </div>
            </div>
            `;
            hideLectureContainer(id);

            const lectureElement = document.createElement('div');
            lectureElement.innerHTML = lecture;


            let lectureContainer = containerId.replace('lectureContainer', 'lectures');
            document.getElementById(lectureContainer).appendChild(lectureElement.firstChild);

            
            // Start Message 

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'success', 
                    showConfirmButton: false,
                    timer: 6000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success',
                    title: data.success, 
                    })

            }else{
                Toast.fire({
                    type: 'error',
                    title: data.error, 
                })
            }

            // End Message
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}
</script>

@endsection