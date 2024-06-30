@extends('frontend.main')

@section('content')


@section('title')
All Blogs | Easy Learning
@endsection

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">All Blogs</h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="index.html">Home</a></li>
                <li>Blogs</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<!-- ================================
    START BLOG AREA
================================= -->
<section class="blog-area section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5">
                <div class="row">
                    @foreach ($posts as $post)
                        <div class="col-lg-6">
                            <div class="card card-item">
                                <div class="card-image">
                                    <a href="{{ route('blog_details', $post->slug) }}" class="d-block">
                                        <img class="card-img-top lazy" src="{{ Storage::url('upload/posts_images/'. $post->image) }}" data-src="images/img8.jpg" alt="Card image cap">
                                    </a>
                                    <div class="course-badge-labels">
                                        <div class="course-badge">{{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</div>
                                    </div>
                                </div><!-- end card-image -->
                                <div class="card-body">
                                    <h5 class="card-title"><a href="{{ route('blog_details', $post->slug) }}">{{ $post->title }}</a></h5>
                                    <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                                        <li class="d-flex align-items-center">By<a href="#">{{ $post->admin->name }}</a></li>
                                        <li class="d-flex align-items-center"><a href="#">{{ count($post->comments) }} Comments</a></li>
                                    <div class="d-flex justify-content-between align-items-center pt-3">
                                        <a href="{{ route('blog_details', $post->slug) }}" class="btn theme-btn theme-btn-sm theme-btn-white">Read More <i class="la la-arrow-right icon ml-1"></i></a>
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col-lg-6 -->
                    @endforeach


                </div><!-- end row -->
                <div class="text-center pt-3">
                    <nav aria-label="Page navigation example" class="pagination-box">
                        {{ $posts->links('vendor.pagination.custom_pagination') }}
                    </nav>
                </div>
            </div><!-- end col-lg-8 -->
            <div class="col-lg-4">
                <div class="sidebar">

                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Categories</h3>
                            <div class="divider"><span></span></div>
                            <ul class="generic-list-item">
                                @foreach ($categories as $category)
                                    <li><a href="{{ route('blog_category_details', $category->id) }}">{{ $category->category_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div><!-- end card -->
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Recent Posts</h3>
                            <div class="divider"><span></span></div>
                            @foreach ($recentPosts as $post)
                                <div class="media media-card border-bottom border-bottom-gray pb-4 mb-4">
                                    <a href="{{ route('blog_details', $post->slug) }}" class="media-img">
                                        <img class="mr-3" src="{{ Storage::url('upload/posts_images/'. $post->image) }}" alt="Related course image">
                                    </a>
                                    <div class="media-body">
                                        <h5 class="fs-15"><a href="{{ route('blog_details', $post->slug) }}">{{ $post->title }}</a></h5>
                                        <span class="d-block lh-18 py-1 fs-14">{{ $post->admin->name }}</span>
                                    </div>
                                </div><!-- end media -->
                            @endforeach

                            <div class="view-all-course-btn-box">
                                <a href="blog-no-sidebar.html" class="btn theme-btn w-100">View All Posts <i class="la la-arrow-right icon ml-1"></i></a>
                            </div>
                        </div>
                    </div><!-- end card -->
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Sidebar Form</h3>
                            <div class="divider"><span></span></div>
                            <form method="post">
                                <div class="form-group">
                                    <input class="form-control form--control" type="text" name="text" placeholder="Name">
                                    <span class="la la-user input-icon"></span>
                                </div>
                                <div class="form-group">
                                    <input class="form-control form--control" type="email" name="email" placeholder="Email">
                                    <span class="la la-envelope input-icon"></span>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control form--control pl-3" name="message" rows="4" placeholder="Write message"></textarea>
                                </div>
                                <div class="btn-box">
                                    <button class="btn theme-btn w-100">Contact Author <i class="la la-arrow-right icon ml-1"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Connect & Follow</h3>
                            <div class="divider"><span></span></div>
                            <ul class="social-icons social-icons-styled social--icons-styled">
                                <li><a href="#"><i class="la la-facebook"></i></a></li>
                                <li><a href="#"><i class="la la-twitter"></i></a></li>
                                <li><a href="#"><i class="la la-instagram"></i></a></li>
                                <li><a href="#"><i class="la la-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div><!-- end card -->
                </div><!-- end sidebar -->
            </div><!-- end col-lg-4 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end blog-area -->
<!-- ================================
    END BLOG AREA
================================= -->


@endsection