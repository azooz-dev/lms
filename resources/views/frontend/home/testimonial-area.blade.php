<section class="testimonial-area section-padding">
    <div class="container">
        <div class="section-heading text-center">
            <h5 class="ribbon ribbon-lg mb-2">Testimonials</h5>
            <h2 class="section__title">Student's Feedback</h2>
            <span class="section-divider"></span>
        </div><!-- end section-heading -->
    </div><!-- end container -->
    <div class="container-fluid">
        <div class="testimonial-carousel owl-action-styled">
            @foreach ($reviews as $review)
                <div class="card card-item">
                    <div class="card-body">
                        <div class="media media-card align-items-center pb-3">
                            <div class="media-img avatar-md">
                                <img src="{{ (!empty($review->user->photo)) ? Storage::url('public/upload/users_images/'. $review->user->photo) : asset('storage/upload/images.jpg') }}" alt="Testimonial avatar" class="rounded-full">
                            </div>
                            <div class="media-body">
                                <h5>{{ $review->user->name }}</h5>
                                <div class="d-flex align-items-center pt-1">
                                    <span class="lh-18 pr-2">Student</span>
                                    <div class="review-stars">
                                        @switch($review->rating)
                                            @case('1')
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star"></span>
                                                @break
                                            @case('2')
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                @break
                                            @case('3')
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                @break
                                            @case('4')
                                                <span class="la la-star-o"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                @break
                                            @case('5')
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                @break
                                        
                                            @default
                                                    
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div><!-- end media -->
                        <p class="card-text">
                            {{ $review->message }}
                        </p>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            @endforeach
        </div><!-- end testimonial-carousel -->
    </div><!-- container-fluid -->
</section><!-- end testimonial-area -->