<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="author" content="TechyDevs">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Course Details | Easy Learning</title>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" sizes="16x16" href="{{ asset('frontend/images/favicon.png') }}">

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/line-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animated-headline.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/plyr.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery-te-1.4.0.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <!-- end inject -->

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>

<!-- start cssload-loader -->
<div class="preloader">
    <div class="loader">
        <svg class="spinner" viewBox="0 0 50 50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
        </svg>
    </div>
</div>
<!-- end cssload-loader -->

<!--======================================
        START HEADER AREA
    ======================================-->
    @include('frontend.course.body.header', [
        'course' => $course
    ])
<!--======================================
        END HEADER AREA
======================================-->

<!--======================================
        START COURSE-DASHBOARD
======================================-->
<section class="course-dashboard">
    <div class="course-dashboard-wrap">
        <div class="course-dashboard-container d-flex">
            <div class="course-dashboard-column">
                <div class="lecture-viewer-container">
                    <div class="lecture-video-item">
                        <iframe width="100%" height="500" id="videoContainer" src="" title="The Best Way to Learn With Videos and Online Classes I Video Notebook" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        <div id="textLesson" class="fs-24 font-weight-semi-bold pb-2 text-center mt-4">
                            <h3></h3>
                        </div> 

                    </div> 
                </div><!-- end lecture-viewer-container -->

                <div class="lecture-video-detail">
                    <div class="lecture-tab-body bg-gray p-4">
                        <ul class="nav nav-tabs generic-tab" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" id="search-tab" data-toggle="tab" href="#search" role="tab" aria-controls="search" aria-selected="false">
                                    <i class="la la-search"></i>
                                </a>
                            </li>
                            <li class="nav-item mobile-menu-nav-item">
                                <a class="nav-link" id="course-content-tab" data-toggle="tab" href="#course-content" role="tab" aria-controls="course-content" aria-selected="false">
                                    Course Content
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="question-and-ans-tab" data-toggle="tab" href="#question-and-ans" role="tab" aria-controls="question-and-ans" aria-selected="false">
                                    Question & Ans
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="announcements-tab" data-toggle="tab" href="#announcements" role="tab" aria-controls="announcements" aria-selected="false">
                                    Announcements
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="lecture-video-detail-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="search" role="tabpanel" aria-labelledby="search-tab">
                                <div class="search-course-wrap pt-40px">
                                    <form action="#" class="pb-5">
                                        <div class="input-group">
                                            <input class="form-control form--control form--control-gray pl-3" type="text" name="search" placeholder="Search course content">
                                            <div class="input-group-append">
                                                <button class="btn theme-btn"><span class="la la-search"></span></button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="search-results-message text-center">
                                        <h3 class="fs-24 font-weight-semi-bold pb-1">Start a new search</h3>
                                        <p>To find captions, lectures or resources</p>
                                    </div>
                                </div><!-- end search-course-wrap -->
                            </div><!-- end tab-pane -->
                            <div class="tab-pane fade" id="course-content" role="tabpanel" aria-labelledby="course-content-tab">
                                <div class="mobile-course-menu pt-4">
                                    <div class="accordion generic-accordion generic--accordion" id="mobileCourseAccordionCourseExample">
                                        <div class="card">
                                            <div class="card-header" id="mobileCourseHeadingOne">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#mobileCourseCollapseOne" aria-expanded="true" aria-controls="mobileCourseCollapseOne">
                                                    <i class="la la-angle-down"></i>
                                                    <i class="la la-angle-up"></i>
                                                    <span class="fs-15"> Section 1: Dive in and Discover After Effects</span>
                                                    <span class="course-duration">
                                                        <span>1/5</span>
                                                        <span>21min</span>
                                                    </span>
                                                </button>
                                            </div><!-- end card-header -->
                                            <div id="mobileCourseCollapseOne" class="collapse show" aria-labelledby="mobileCourseHeadingOne" data-parent="#mobileCourseAccordionCourseExample">
                                                <div class="card-body p-0">
                                                    <ul class="curriculum-sidebar-list">
                                                        <li class="course-item-link active">
                                                            <div class="course-item-content-wrap">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="mobileCourseCheckbox" required>
                                                                    <label class="custom-control-label custom--control-label" for="mobileCourseCheckbox"></label>
                                                                </div><!-- end custom-control -->
                                                                <div class="course-item-content">
                                                                    <h4 class="fs-15">1. Let's Have Fun - Seriously!</h4>
                                                                    <div class="courser-item-meta-wrap">
                                                                        <p class="course-item-meta"><i class="la la-play-circle"></i>2min</p>
                                                                    </div>
                                                                </div><!-- end course-item-content -->
                                                            </div><!-- end course-item-content-wrap -->
                                                        </li>
                                                        <li class="course-item-link">
                                                            <div class="course-item-content-wrap">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="mobileCourseCheckbox2" required>
                                                                    <label class="custom-control-label custom--control-label" for="mobileCourseCheckbox2"></label>
                                                                </div><!-- end custom-control -->
                                                                <div class="course-item-content">
                                                                    <h4 class="fs-15">2. A simple concept to get ahead</h4>
                                                                    <div class="courser-item-meta-wrap">
                                                                        <p class="course-item-meta"><i class="la la-play-circle"></i>2min</p>
                                                                    </div>
                                                                </div><!-- end course-item-content -->
                                                            </div><!-- end course-item-content-wrap -->
                                                        </li>
                                                        <li class="course-item-link active-resource">
                                                            <div class="course-item-content-wrap">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="mobileCourseCheckbox3" required>
                                                                    <label class="custom-control-label custom--control-label" for="mobileCourseCheckbox3"></label>
                                                                </div><!-- end custom-control -->
                                                                <div class="course-item-content">
                                                                    <h4 class="fs-15">3. Download your Footage for your Quick Start</h4>
                                                                    <div class="courser-item-meta-wrap">
                                                                        <p class="course-item-meta"><i class="la la-file"></i>2min</p>
                                                                        <div class="generic-action-wrap">
                                                                            <div class="dropdown">
                                                                                <a class="btn theme-btn theme-btn-sm theme-btn-transparent mt-1 fs-14 font-weight-medium" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="la la-folder-open mr-1"></i> Resources<i class="la la-angle-down ml-1"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                    <a class="dropdown-item" href="javascript:void(0)">
                                                                                        Section-Footage.zip
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div><!-- end generic-action-wrap -->
                                                                    </div>
                                                                </div><!-- end course-item-content -->
                                                            </div><!-- end course-item-content-wrap -->
                                                        </li>
                                                        <li class="course-item-link">
                                                            <div class="course-item-content-wrap">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="mobileCourseCheckbox4" required>
                                                                    <label class="custom-control-label custom--control-label" for="mobileCourseCheckbox4"></label>
                                                                </div><!-- end custom-control -->
                                                                <div class="course-item-content">
                                                                    <h4 class="fs-15">4. Jump in and Animate your Character</h4>
                                                                    <div class="courser-item-meta-wrap">
                                                                        <p class="course-item-meta"><i class="la la-play-circle"></i>2min</p>
                                                                    </div>
                                                                </div><!-- end course-item-content -->
                                                            </div><!-- end course-item-content-wrap -->
                                                        </li>
                                                    </ul>
                                                </div><!-- end card-body -->
                                            </div><!-- end collapse -->
                                        </div><!-- end card -->

                                    </div><!-- end accordion-->
                                </div><!-- end mobile-course-menu -->
                            </div><!-- end tab-pane -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                <div class="lecture-overview-wrap">
                                    <div class="lecture-overview-item">
                                        <h3 class="fs-24 font-weight-semi-bold pb-2">About this course</h3>
                                        <p>{{ $course->title }}</p>
                                    </div><!-- end lecture-overview-item -->
                                    <div class="section-block"></div>
                                    <div class="lecture-overview-item">
                                        <div class="lecture-overview-stats-wrap d-flex">
                                            <div class="lecture-overview-stats-item">
                                                <h3 class="fs-16 font-weight-semi-bold pb-2">By the numbers</h3>
                                            </div><!-- end lecture-overview-stats-item -->
                                            <div class="lecture-overview-stats-item">
                                                <ul class="generic-list-item">
                                                    <li><span>Skill level:</span>{{ $course->course_level }}</li>
                                                    {{-- <li><span>Students:</span>83950</li> --}}
                                                    <li><span>Languages:</span>English</li>
                                                    <li><span>Resources:</span>{{ $course->resources }}</li>
                                                    <li><span>Captions:</span>Yes</li>
                                                </ul>
                                            </div><!-- end lecture-overview-stats-item -->
                                            <div class="lecture-overview-stats-item">
                                                <ul class="generic-list-item">
                                                    <li><span>Lectures:</span>{{ count($course->lectures) }}</li>
                                                    <li><span>Course Duration:</span>{{ $course->duration }} total hours</li>
                                                    <li><span>Certificate:</span>{{ $course->certificate }}</li>
                                                </ul>
                                            </div><!-- end lecture-overview-stats-item -->
                                        </div><!-- end lecture-overview-stats-wrap -->
                                    </div><!-- end lecture-overview-item -->
                                    <div class="section-block"></div>
                                    <div class="lecture-overview-item">
                                        <div class="lecture-overview-stats-wrap d-flex">
                                            <div class="lecture-overview-stats-item">
                                                <h3 class="fs-16 font-weight-semi-bold pb-2">Certificates</h3>
                                            </div><!-- end lecture-overview-stats-item -->
                                            <div class="lecture-overview-stats-item lecture-overview-stats-wide-item">
                                                <p class="pb-3">Get Aduca certificate by completing entire course</p>
                                                <a href="#" class="btn theme-btn theme-btn-transparent">Aduca Certificate</a>
                                            </div><!-- end lecture-overview-stats-item -->
                                        </div><!-- end lecture-overview-stats-wrap -->
                                    </div><!-- end lecture-overview-item -->
                                    <div class="section-block"></div>
                                    <div class="lecture-overview-item">
                                        <div class="lecture-overview-stats-wrap d-flex">
                                            <div class="lecture-overview-stats-item">
                                                <h3 class="fs-16 font-weight-semi-bold pb-2">Features</h3>
                                            </div><!-- end lecture-overview-stats-item -->
                                            <div class="lecture-overview-stats-item">
                                                <p>Available on <a href="#" class="text-color hover-underline">IOS</a> and <a href="#" class="text-color hover-underline">Android</a></p>
                                            </div><!-- end lecture-overview-stats-item -->
                                        </div><!-- end lecture-overview-stats-wrap -->
                                    </div><!-- end lecture-overview-item -->
                                    <div class="section-block"></div>
                                    <div class="lecture-overview-item">
                                        <div class="lecture-overview-stats-wrap d-flex">
                                            <div class="lecture-overview-stats-item">
                                                <h3 class="fs-16 font-weight-semi-bold pb-2">Description</h3>
                                            </div><!-- end lecture-overview-stats-item -->
                                            <div class="lecture-overview-stats-item lecture-overview-stats-wide-item lecture-description">
                                                <h3 class="fs-16 font-weight-semi-bold pb-2">From the Author of the Best Selling After Effects CC 2020 Complete Course</h3>
                                                <p>{!! $course->description !!}</p>
                                                <div class="collapse" id="collapseMore">

                                                </div>

                                            </div><!-- end lecture-overview-stats-item -->
                                        </div><!-- end lecture-overview-stats-wrap -->
                                    </div><!-- end lecture-overview-item -->
                                    <div class="section-block"></div>
                                </div><!-- end lecture-overview-wrap -->
                            </div><!-- end tab-pane -->
                            <div class="tab-pane fade" id="question-and-ans" role="tabpanel" aria-labelledby="question-and-ans-tab">
                                <div class="lecture-overview-wrap lecture-quest-wrap">
                                    <div class="new-question-wrap">
                                        <button class="btn theme-btn theme-btn-transparent back-to-question-btn"><i class="la la-reply mr-1"></i>Back to all questions</button>
                                        <div class="new-question-body pt-40px">
                                            <h3 class="fs-20 font-weight-semi-bold">My question relates to</h3>
                                            <form action="{{ route('user.send_question', ['course' => $course->id, 'instructor' => $course->instructor->id, 'user' => Auth::user()->id]) }}" method="POST" class="pt-4">
                                                @csrf
                                                <div class="custom-control-wrap">
                                                    <div class="custom-control custom-radio mb-3 pl-0">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control form--control pl-3" name="subject" placeholder="Write your subject...">
                                                        </div>

                                                        <div class="form-group">
                                                            <textarea class="form-control form--control pl-3" name="question" rows="4" placeholder="Write your question..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="btn-box text-center">
                                                    <button type="submit" class="btn theme-btn w-100">Submit Question <i class="la la-arrow-right icon ml-1"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div><!-- end new-question-wrap -->

                                            </div><!-- end question-list-item -->
                                        </div><!-- end replay-question-body -->
                                    </div><!-- end replay-question-wrap -->

                                    <div class="question-overview-result-wrap">
                                        <div class="lecture-overview-item">
                                            <div class="question-overview-result-header d-flex align-items-center justify-content-between">
                                                <h3 class="fs-17 font-weight-semi-bold">{{ count($course->questions) }} questions in this course</h3>
                                                <button class="btn theme-btn theme-btn-sm theme-btn-transparent ask-new-question-btn">Ask a new question</button>
                                            </div>
                                        </div><!-- end lecture-overview-item -->
                                        <div class="section-block"></div>
                                        <div class="lecture-overview-item mt-0">
                                            <div class="question-list-item">
                                                @foreach ($course->questions as $question)
                                                    <div class="media media-card border-bottom border-bottom-gray py-4 px-3">
                                                        <div class="media-img rounded-full flex-shrink-0 avatar-sm">
                                                            <img class="rounded-full" src="{{ (!empty($question->user->photo)) ? Storage::url('public/upload/users_images/'. $question->user->photo) : asset('storage/upload/images.jpg') }}" alt="User image">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="question-meta-content">
                                                                    <a href="javascript:void(0)" class="d-block">
                                                                        <h5 class="fs-16 pb-1">{{ $question->user->name }}</h5>
                                                                        <p class="text-truncate fs-15 text-gray">{{ $question->question }}</p>
                                                                    </a>
                                                                </div><!-- end question-meta-content -->
                                                                <div class="question-upvote-action">
                                                                    <div class="number-upvotes pb-2 d-flex align-items-center">
                                                                    </div>
                                                                </div><!-- end question-upvote-action -->
                                                            </div>
                                                            <p class="meta-tags pt-1 fs-13">
                                                                <a href="{{ route('instructor_details', $question->instructor->id) }}">{{ $question->instructor->name }}</a>
                                                                <span>{{  \Carbon\Carbon::parse($question->created_at)->diffForHumans() }}</span>
                                                            </p>
                                                        </div><!-- end media-body -->
                                                    </div><!-- end media -->

                                                    @foreach ($question->replies as $reply)
                                                        <div class="media media-card border-bottom border-bottom-gray py-4 px-3" style="background-color: #f7f7ff;">
                                                            <div class="media-img rounded-full flex-shrink-0 avatar-sm">
                                                                <img class="rounded-full" src="{{ (!empty($reply->instructor->photo)) ? Storage::url('public/upload/users_images/'. $reply->instructor->photo) : asset('storage/upload/images.jpg') }}" alt="User image">
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="question-meta-content">
                                                                        <a href="javascript:void(0)" class="d-block">
                                                                            <h5 class="fs-16 pb-1">{{ $question->instructor->name }}</h5>
                                                                            <p class="text-truncate fs-15 text-gray">{{ $reply->reply }}</p>
                                                                        </a>
                                                                    </div><!-- end question-meta-content -->
                                                                    <div class="question-upvote-action">
                                                                        <div class="number-upvotes pb-2 d-flex align-items-center">
                                                                        </div>
                                                                    </div><!-- end question-upvote-action -->
                                                                </div>
                                                                <p class="meta-tags pt-1 fs-13">
                                                                    <span>{{  \Carbon\Carbon::parse($question->created_at)->diffForHumans() }}</span>
                                                                </p>
                                                            </div><!-- end media-body -->
                                                        </div><!-- end media -->

                                                    @endforeach


                                                @endforeach

                                            </div>
                                            <div class="question-btn-box pt-35px text-center">
                                                <button class="btn theme-btn theme-btn-transparent w-100" type="button">See More</button>
                                            </div>
                                        </div><!-- end lecture-overview-item -->
                                    </div>
                                </div>
                            </div><!-- end tab-pane -->
                            <div class="tab-pane fade" id="announcements" role="tabpanel" aria-labelledby="announcements-tab">
                                <div class="lecture-overview-wrap lecture-announcement-wrap">
                                    <div class="lecture-overview-item">
                                        <div class="media media-card align-items-center">
                                            <a href="teacher-detail.html" class="media-img d-block rounded-full avatar-md">
                                                <img src="{{ asset('frontend/images/small-avatar-1.jpg') }}" alt="Instructor avatar" class="rounded-full">
                                            </a>
                                            <div class="media-body">
                                                <h5 class="pb-1"><a href="teacher-detail.html">Alex Smith</a></h5>
                                                <div class="announcement-meta fs-15">
                                                    <span>Posted an announcement</span>
                                                    <span> · 3 years ago ·</span>
                                                    <a href="#" class="btn-text" data-toggle="modal" data-target="#reportModal" title="Report abuse"><i class="la la-flag"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="lecture-owner-decription pt-4">
                                            <h3 class="fs-19 font-weight-semi-bold pb-3">Important Q&A support</h3>
                                            <p>Happy 2019 to everyone, thank you for being a student and all of your support.</p>
                                            <p><strong>Great job on enrolling and your current course progress.  I encourage you keep in pursuit of your dreams :)</strong></p>
                                            <p>The whole lot. In my course After Effects Complete Course packed with all Techniques and Methods (No Tricks and gimmicks).</p>
                                            <p class="font-italic"><strong>Unfortunately this will result in delayed responses by me in the Q&A section and to direct messages.  This is only for the next week  and once back I will be back to 100% .</strong></p>
                                            <p>I will continue to do my best to respond to as many questions as possible but only one person, regularly I spend 4-5 hours daily on this and with over 440000 students as you can image that its a lot of work.</p>
                                            <p class="font-italic">Thank you once again for your understanding and for all of the wonderful students who I have had an opportunity to communicate with regularly and all of your encouragement.</p>
                                            <p>Have an awesome day</p>
                                            <p>Alex</p>
                                        </div>
                                        <div class="lecture-announcement-comment-wrap pt-4">
                                            <div class="media media-card align-items-center">
                                                <div class="media-img rounded-full avatar-sm flex-shrink-0">
                                                    <img src="{{ asset('frontend/images/small-avatar-1.jpg') }}" alt="Instructor avatar" class="rounded-full">
                                                </div><!-- end media-img -->
                                                <div class="media-body">
                                                    <form action="#">
                                                        <div class="input-group">
                                                            <input class="form-control form--control form--control-gray pl-3" type="text" name="search" placeholder="Enter your comment">
                                                            <div class="input-group-append">
                                                                <button class="btn theme-btn" type="button"><i class="la la-arrow-right"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div><!-- end media-body -->
                                            </div><!-- end media -->
                                            <div class="comments pt-40px">
                                                <div class="media media-card mb-3 border-bottom border-bottom-gray pb-3">
                                                    <div class="media-img rounded-full avatar-sm flex-shrink-0">
                                                        <img src="{{ asset('frontend/images/small-avatar-2.jpg') }}" alt="Instructor avatar" class="rounded-full">
                                                    </div><!-- end media-img -->
                                                    <div class="media-body">
                                                        <div class="announcement-meta fs-15 lh-20">
                                                            <a href="#" class="text-color">Tony Olsson</a>
                                                            <span> · 3 years ago ·</span>
                                                            <a href="#" class="btn-text" data-toggle="modal" data-target="#reportModal" title="Report abuse"><i class="la la-flag"></i></a>
                                                        </div>
                                                        <p class="pt-1">Occaecati cupiditate non provident, similique sunt in culpa fuga.</p>
                                                    </div><!-- end media-body -->
                                                </div><!-- end media -->
                                                <div class="media media-card mb-3 border-bottom border-bottom-gray pb-3">
                                                    <div class="media-img rounded-full avatar-sm flex-shrink-0">
                                                        <img src="{{ asset('frontend/images/small-avatar-3.jpg') }}" alt="Instructor avatar" class="rounded-full">
                                                    </div><!-- end media-img -->
                                                    <div class="media-body">
                                                        <div class="announcement-meta fs-15 lh-20">
                                                            <a href="#" class="text-color">Eduard-Dan</a>
                                                            <span> · 2 years ago ·</span>
                                                            <a href="#" class="btn-text" data-toggle="modal" data-target="#reportModal" title="Report abuse"><i class="la la-flag"></i></a>
                                                        </div>
                                                        <p class="pt-1">Occaecati cupiditate non provident, similique sunt in culpa fuga.</p>
                                                    </div><!-- end media-body -->
                                                </div><!-- end media -->
                                            </div><!-- end comments -->
                                        </div><!-- end lecture-announcement-comment-wrap -->
                                    </div><!-- end lecture-overview-item -->
                                </div>
                            </div><!-- end tab-pane -->
                        </div><!-- end tab-content -->
                    </div><!-- end lecture-video-detail-body -->
                </div><!-- end lecture-video-detail -->
                <div class="cta-area py-4 bg-gray">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="cta-content-wrap">
                                    <h3 class="fs-18 font-weight-semi-bold">Top companies choose <a href="for-business.html" class="text-color hover-underline">Aduca for Business</a> to build in-demand career skills.</h3>
                                </div>
                            </div><!-- end col-lg-6 -->
                            <div class="col-lg-6">
                                <div class="client-logo-wrap text-right">
                                    <a href="#" class="client-logo-item client--logo-item-2 pr-3"><img src="{{ asset('frontend/images/sponsor-img.png') }}" alt="brand image"></a>
                                    <a href="#" class="client-logo-item client--logo-item-2 pr-3"><img src="{{ asset('frontend/images/sponsor-img2.png') }}" alt="brand image"></a>
                                    <a href="#" class="client-logo-item client--logo-item-2 pr-3"><img src="{{ asset('frontend/images/sponsor-img3.png') }}" alt="brand image"></a>
                                </div><!-- end client-logo-wrap -->
                            </div><!-- end col-lg-6 -->
                        </div><!-- end row -->
                    </div><!-- end container-fluid -->
                </div><!-- end cta-area -->

                <!--======================================
                    START FOOTER AREA
                ======================================-->
                @include('frontend.course.body.footer')
                <!--======================================
                        END FOOTER AREA
                ======================================-->

            </div><!-- end course-dashboard-column -->
            <div class="course-dashboard-sidebar-column">
                <button class="sidebar-open" type="button"><i class="la la-angle-left"></i> Course content</button>
                <div class="course-dashboard-sidebar-wrap custom-scrollbar-styled">
                    <div class="course-dashboard-side-heading d-flex align-items-center justify-content-between">
                        <h3 class="fs-18 font-weight-semi-bold">Course content</h3>
                        <button class="sidebar-close" type="button"><i class="la la-times"></i></button>
                    </div><!-- end course-dashboard-side-heading -->
                    <div class="course-dashboard-side-content">
                        <div class="accordion generic-accordion generic--accordion" id="accordionCourseExample">
                            @foreach ($course->sections as $key_section => $section)
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne{{ $section->id }}" aria-expanded="true" aria-controls="collapseOne{{ $section->id }}">
                                        <i class="la la-angle-down"></i>
                                        <i class="la la-angle-up"></i>
                                        <span class="fs-15"> Section {{ $key_section + 1 }}: {{ $section->section_title }}</span>
                                        {{-- <span class="course-duration">
                                            <span>{{ $key_section + 1 }}/{{ count($section->lectures) }}</span>
                                        </span> --}}
                                    </button>
                                </div><!-- end card-header -->
                                <div id="collapseOne{{ $section->id }}" class="collapse @if ($key_section == 0) show @endif" aria-labelledby="headingOne" data-parent="#accordionCourseExample">
                                    <div class="card-body p-0">
                                        <ul class="curriculum-sidebar-list">
                                            @foreach ($section->lectures as $key_lecture => $lecture)
                                            <li class="course-item-link @if ($key_lecture == 0 && $key_section == 0) active @endif">
                                                <div class="course-item-content-wrap">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="courseCheckbox" required>
                                                        <label class="custom-control-label custom--control-label" for="courseCheckbox"></label>
                                                    </div><!-- end custom-control -->
                                                    <div class="course-item-content">
                                                        <h4 class="fs-15 lecture-title" data-video-url="{{ $lecture->url }}" data-content="{!! $lecture->content !!}">{{ $key_lecture + 1 }}. {{ $lecture->lecture_title }}</h4>
                                                    </div><!-- end course-item-content -->
                                                </div><!-- end course-item-content-wrap -->
                                            </li>

                                            @endforeach

                                    </div><!-- end card-body -->
                                </div><!-- end collapse -->
                            </div><!-- end card -->
                            @endforeach

                        </div><!-- end accordion-->
                    </div><!-- end course-dashboard-side-content -->
                </div><!-- end course-dashboard-sidebar-wrap -->
            </div><!-- end course-dashboard-sidebar-column -->
        </div><!-- end course-dashboard-container -->
    </div><!-- end course-dashboard-wrap -->
</section><!-- end course-dashboard -->
<!--======================================
        END COURSE-DASHBOARD
======================================-->

<!-- start scroll top -->
<div id="scroll-top">
    <i class="la la-arrow-up" title="Go top"></i>
</div>
<!-- end scroll top -->

<!-- Modal -->
<div class="modal fade modal-container" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-gray">
                <div class="pr-2">
                    <h5 class="modal-title fs-19 font-weight-semi-bold lh-24" id="ratingModalTitle">
                        How would you rate this course?
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-times"></span>
                </button>
            </div><!-- end modal-header -->
            <div class="modal-body text-center py-5">
                <div class="leave-rating mt-5">
                    <input type="radio" name='rate' id="star5"/>
                    <label for="star5" class="fs-45"></label>
                    <input type="radio" name='rate' id="star4"/>
                    <label for="star4" class="fs-45"></label>
                    <input type="radio" name='rate' id="star3"/>
                    <label for="star3" class="fs-45"></label>
                    <input type="radio" name='rate' id="star2"/>
                    <label for="star2" class="fs-45"></label>
                    <input type="radio" name='rate' id="star1"/>
                    <label for="star1" class="fs-45"></label>
                    <div class="rating-result-text fs-20 pb-4"></div>
                </div><!-- end leave-rating -->
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal -->

<!-- Modal -->
<div class="modal fade modal-container" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-gray">
                <h5 class="modal-title fs-19 font-weight-semi-bold" id="shareModalTitle">Share this course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-times"></span>
                </button>
            </div><!-- end modal-header -->
            <div class="modal-body">
                <div class="copy-to-clipboard">
                    <span class="success-message">Copied!</span>
                    <div class="input-group">
                        <input type="text" class="form-control form--control copy-input pl-3" value="https://www.aduca.com/share/101WxMB0oac1hVQQ==/">
                        <div class="input-group-append">
                            <button class="btn theme-btn theme-btn-sm copy-btn shadow-none"><i class="la la-copy mr-1"></i> Copy</button>
                        </div>
                    </div>
                </div><!-- end copy-to-clipboard -->
            </div><!-- end modal-body -->
            <div class="modal-footer justify-content-center border-top-gray">
                <ul class="social-icons social-icons-styled">
                    <li><a href="#" class="facebook-bg"><i class="la la-facebook"></i></a></li>
                    <li><a href="#" class="twitter-bg"><i class="la la-twitter"></i></a></li>
                    <li><a href="#" class="instagram-bg"><i class="la la-instagram"></i></a></li>
                </ul>
            </div><!-- end modal-footer -->
        </div><!-- end modal-content-->
    </div><!-- end modal-dialog -->
</div><!-- end modal -->

<!-- Modal -->
<div class="modal fade modal-container" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-gray">
                <div class="pr-2">
                    <h5 class="modal-title fs-19 font-weight-semi-bold lh-24" id="reportModalTitle">Report Abuse</h5>
                    <p class="pt-1 fs-14 lh-24">Flagged content is reviewed by Aduca staff to determine whether it violates Terms of Service or Community Guidelines. If you have a question or technical issue, please contact our
                        <a href="contact.html" class="text-color hover-underline">Support team here</a>.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-times"></span>
                </button>
            </div><!-- end modal-header -->
            <div class="modal-body">
                <form method="post">
                    <div class="input-box">
                        <label class="label-text">Select Report Type</label>
                        <div class="form-group">
                            <div class="select-container w-auto">
                                <select class="select-container-select">
                                    <option value>-- Select One --</option>
                                    <option value="1">Inappropriate Course Content</option>
                                    <option value="2">Inappropriate Behavior</option>
                                    <option value="3">Aduca Policy Violation</option>
                                    <option value="4">Spammy Content</option>
                                    <option value="5">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="input-box">
                        <label class="label-text">Write Message</label>
                        <div class="form-group">
                            <textarea class="form-control form--control pl-3" name="message" placeholder="Provide additional details here..." rows="5"></textarea>
                        </div>
                    </div>
                    <div class="btn-box text-right pt-2">
                        <button type="button" class="btn font-weight-medium mr-3" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn theme-btn theme-btn-sm lh-30">Submit <i class="la la-arrow-right icon ml-1"></i></button>
                    </div>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal -->

<!-- Modal -->
<div class="modal fade modal-container" id="insertLinkModal" tabindex="-1" role="dialog" aria-labelledby="insertLinkModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-gray">
                <div class="pr-2">
                    <h5 class="modal-title fs-19 font-weight-semi-bold lh-24" id="insertLinkModalTitle">Insert Link</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-times"></span>
                </button>
            </div><!-- end modal-header -->
            <div class="modal-body">
                <form action="#">
                    <div class="input-box">
                        <label class="label-text">URL</label>
                        <div class="form-group">
                            <input class="form-control form--control" type="text" name="text" placeholder="Url">
                            <i class="la la-link input-icon"></i>
                        </div>
                    </div>
                    <div class="input-box">
                        <label class="label-text">Text</label>
                        <div class="form-group">
                            <input class="form-control form--control" type="text" name="text" placeholder="Text">
                            <i class="la la-pencil input-icon"></i>
                        </div>
                    </div>
                    <div class="btn-box text-right pt-2">
                        <button type="button" class="btn font-weight-medium mr-3" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn theme-btn theme-btn-sm lh-30">Insert <i class="la la-arrow-right icon ml-1"></i></button>
                    </div>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal -->

<!-- Modal -->
<div class="modal fade modal-container" id="uploadPhotoModal" tabindex="-1" role="dialog" aria-labelledby="uploadPhotoModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-gray">
                <div class="pr-2">
                    <h5 class="modal-title fs-19 font-weight-semi-bold lh-24" id="uploadPhotoModalTitle">Upload an Image</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-times"></span>
                </button>
            </div><!-- end modal-header -->
            <div class="modal-body">
                <div class="file-upload-wrap">
                    <input type="file" name="files[]" class="multi file-upload-input" multiple>
                    <span class="file-upload-text"><i class="la la-upload mr-2"></i>Drop files here or click to upload</span>
                </div><!-- file-upload-wrap -->
                <div class="btn-box text-right pt-2">
                    <button type="button" class="btn font-weight-medium mr-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn theme-btn theme-btn-sm lh-30">Submit <i class="la la-arrow-right icon ml-1"></i></button>
                </div>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal -->



<!-- template js files -->
<script src="{{ asset('frontend/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/js/isotope.js') }}"></script>
<script src="{{ asset('frontend/js/waypoint.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('frontend/js/fancybox.js') }}"></script>
<script src="{{ asset('frontend/js/plyr.js') }}"></script>
<script src="{{ asset('frontend/js/datedropper.min.js') }}"></script>
<script src="{{ asset('frontend/js/emojionearea.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-te-1.4.0.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.MultiFile.min.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<script>
    var player = new Plyr('#player');
</script>


<script type="text/javascript">
    // Function to open the first lecture when the page loads
    function openFirstLecture() {
        const firstLecture = document.querySelector('.lecture-title'); // Get the first lecture element
        if (firstLecture) {
            firstLecture.click(); // Trigger the click event on the first lecture
        }
    }

    // Function to handle lecture clicks and load content
    function viewLesson(videoUrl, vimeoUrl, textContent) {
        const video = document.getElementById("videoContainer");
        const text = document.getElementById("textLesson");
        const textContainer = document.createElement("div");

        if (videoUrl && videoUrl.trim() !== "") {
            video.classList.remove("d-none");
            text.classList.add("d-none");
            text.innerHTML = "";
            video.setAttribute("src", videoUrl);
        } else if (vimeoUrl && vimeoUrl.trim() !== "") {
            video.classList.remove("d-none");
            text.classList.add("d-none");
            text.innerHTML = "";
            video.setAttribute("src", vimeoUrl);
        } else if (textContent && textContent.trim() !== "") {
            video.classList.add("d-none");
            text.classList.remove("d-none");
            text.innerHTML = "";
            textContainer.innerText = textContent;
            textContainer.style.fontSize = "14px";
            textContainer.style.textAlign = "left";
            textContainer.style.paddingLeft = "40px";
            textContainer.style.paddingRight = "40px";
            text.appendChild(textContainer);
        }
    }

    // Add a click event listener to all lecture elements
    document.querySelectorAll('.lecture-title').forEach((lectureTitle) => {
        lectureTitle.addEventListener('click', () => {
            const videoUrl = lectureTitle.getAttribute('data-video-url');
            const vimeoUrl = lectureTitle.getAttribute('data-vimeo-url');
            const textContent = lectureTitle.getAttribute('data-content');
            viewLesson(videoUrl, vimeoUrl, textContent);
        });
    });

    // Open the first lecture when the page loads
    window.addEventListener('load', () => {
        openFirstLecture();
    });
</script>




<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type','info') }}"
    switch(type){
        case 'info':
        toastr.info(" {{ Session::get('message') }} ");
        break;

        case 'success':
        toastr.success(" {{ Session::get('message') }} ");
        break;

        case 'warning':
        toastr.warning(" {{ Session::get('message') }} ");
        break;

        case 'error':
        toastr.error(" {{ Session::get('message') }} ");
        break; 
    }
    @endif 
</script>

</body>
</html>