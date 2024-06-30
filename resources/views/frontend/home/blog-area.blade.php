<section class="blog-area section--padding bg-gray overflow-hidden">
    <div class="container">
        <div class="section-heading text-center">
            <h5 class="ribbon ribbon-lg mb-2">News feeds</h5>
            <h2 class="section__title">Latest News & Articles</h2>
            <span class="section-divider"></span>
        </div><!-- end section-heading -->
        <div class="blog-post-carousel owl-action-styled half-shape mt-30px">
            @foreach ($posts as $post)
                <div class="card card-item">
                    <div class="card-image">
                        <a href="{{ route('blog_details', $post->slug) }}" class="d-block">
                            <img class="card-img-top" src="{{ Storage::url('upload/posts_images/'. $post->image) }}" alt="Card image cap">
                        </a>
                        <div class="course-badge-labels">
                            <div class="course-badge">{{  \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</div>
                        </div>
                    </div><!-- end card-image -->
                    <div class="card-body">
                        <h5 class="card-title"><a href="{{ route('blog_details', $post->slug) }}">{{ $post->title }}</a></h5>
                        <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                            <li class="d-flex align-items-center">By<a href="#">{{ $post->admin->name }}</a></li>
                            <li class="d-flex align-items-center"><a href="#">{{ count($post->comments) }} Comments</a></li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="{{ route('blog_details', $post->slug) }}" class="btn theme-btn theme-btn-sm theme-btn-white">Read More <i class="la la-arrow-right icon ml-1"></i></a>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            @endforeach

        </div><!-- end blog-post-carousel -->
    </div><!-- end container -->
</section><!-- end blog-area -->