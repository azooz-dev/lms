@extends('frontend.main')

@section('content')


@section('title')
{{ $post->title }} | Easy Learning
@endsection



<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area pt-80px pb-80px pattern-bg">
    <div class="container">
        <div class="breadcrumb-content">
            <div class="section-heading pb-3">
                <h2 class="section__title">{{ $post->title }}</h2>
            </div>
            <ul class="generic-list-item generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="index.html">Home</a></li>
                <li><a href="blog-no-sidebar.html">Blog</a></li>
                <li>{{ $post->title }}</li>
            </ul>
            <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                <li class="d-flex align-items-center">By<a href="#">{{ $post->admin->name }}</a></li>
                <li class="d-flex align-items-center">{{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</li>
                <li class="d-flex align-items-center"><a href="#comments" class="page-scroll">{{ count($post->comments) }} Comments</a></li>
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
<section class="blog-area pt-100px pb-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5">
                <div class="card card-item">
                    <div class="card-body">
                        <p class="card-text">{!! $post->description !!}</p>
                        <div class="pb-3"></div>
                        <div class="section-block"></div>
                        <h3 class="fs-18 font-weight-semi-bold pt-3">Tags</h3>
                        <div class="d-flex flex-wrap justify-content-between align-items-center pt-3">
                            <ul class="generic-list-item generic-list-item-boxed d-flex flex-wrap fs-15">
                                @foreach ($post->tags as $tag)
                                <li class="mr-2"><a href="#">{{ $tag->name }}</a></li>
                                @endforeach
                            </ul>
                            <div class="share-wrap">
                                <ul class="social-icons social-icons-styled">
                                    <li class="mr-0"><a href="#" class="facebook-bg"><i class="la la-facebook"></i></a></li>
                                    <li class="mr-0"><a href="#" class="twitter-bg"><i class="la la-twitter"></i></a></li>
                                    <li class="mr-0"><a href="#" class="instagram-bg"><i class="la la-instagram"></i></a></li>
                                </ul>
                                <div class="icon-element icon-element-sm shadow-sm cursor-pointer share-toggle" title="Toggle to expand social icons"><i class="la la-share-alt"></i></div>
                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
                <div class="section-block"></div>
              
            </div><!-- end col-lg-8 -->
            <div class="col-lg-4">
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
                            @foreach ($posts as $post)
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