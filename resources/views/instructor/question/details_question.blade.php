@extends('instructor.dashboard')

@section('content')

<div class="page-content">
    <div class="chat-wrapper">
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <div class="d-flex align-items-center">
                    <div class="chat-user-online">
                        <img src="{{ (!empty(Auth::user()->photo)) ? Storage::url('public/upload/instructor_images/'. Auth::user()->photo) : asset('storage/upload/images.jpg') }}" width="45" height="45" class="rounded-circle" alt="" />
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="mb-3"></div>
                <div class="input-group input-group-sm"> <span class="input-group-text bg-transparent"><i class='bx bx-search'></i></span>
                    <input type="text" class="form-control" placeholder="People, groups, & messages"> <span class="input-group-text bg-transparent"><i class='bx bx-dialpad'></i></span>
                </div>

            </div>
            <div class="chat-sidebar-content">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-Chats">
                        <div class="p-3">

                        </div>
                        <div class="chat-list">
                            <div class="list-group list-group-flush">
                                <a href="javascript:;" class="list-group-item">
                                    <div class="d-flex">
                                        <div class="chat-user-online">
                                            <img src="{{ (!empty($user->photo)) ? Storage::url('public/upload/instructor_images/'. $user->photo) : asset('storage/upload/images.jpg') }}" width="42" height="42" class="rounded-circle" alt="" />
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">{{ $user->name }}</h6>
                                            <p class="mb-0 chat-msg">Student</p>
                                        </div>
                                        <div class="chat-time">9:51 AM</div>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-header d-flex align-items-center">
            <div class="chat-toggle-btn"><i class='bx bx-menu-alt-left'></i>
            </div>
            <div>
                <h4 class="mb-1 font-weight-bold">{{ $user->name }}</h4>

            </div>
            <div class="chat-top-header-menu ms-auto"> <a href="javascript:;"><i class='bx bx-video'></i></a>
                <a href="javascript:;"><i class='bx bx-phone'></i></a>
            </div>
        </div>
        <div class="chat-content">
            
            @foreach ($questions as $question)
            <div class="chat-content-leftside">
                <div class="d-flex">
                    <img src="{{ (!empty($question->user->photo)) ? Storage::url('public/upload/instructor_images/'. $question->user->photo) : asset('storage/upload/images.jpg') }}" width="48" height="48" class="rounded-circle" alt="" />
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0 chat-time">{{ $question->user->name }}, {{ $question->created_at->format('g:i A') }}</p>
                        <p class="chat-left-msg">{{ $question->question }}</p>
                    </div>
                </div>
            </div>
                @foreach ($question->replies as $reply)
                <div class="chat-content-rightside">
                    <div class="d-flex ms-auto">
                        <div class="flex-grow-1 me-2">
                            <p class="mb-0 chat-time text-end">you, {{ $reply->created_at->format('g:i A') }}</p>
                            <p class="chat-right-msg">{{ $reply->reply }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            <form action="{{ route('instructor.reply_question', ['user' => $question->user_id, 'course' => $question->course_id]) }}" method="POST">
                @csrf
            @endforeach

        </div>
        <div class="chat-footer d-flex align-items-center">
            <div class="flex-grow-1 pe-2">
                <div class="input-group">	<span class="input-group-text"><i class='bx bx-smile'></i></span>
                    <input type="text" class="form-control" name="reply" placeholder="Type a message">
                </div>
            </div>
            <div class="chat-footer-menu">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </div>
    </form>

        <!--start chat overlay-->
        <div class="overlay chat-toggle-btn-mobile"></div>
        <!--end chat overlay-->
    </div>
</div>

@endsection