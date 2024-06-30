<section class="course-area pb-90px">
    <div class="course-wrapper">
        <div class="container">
            <div class="section-heading text-center">
                <h5 class="ribbon ribbon-lg mb-2">Learn on your schedule</h5>
                <h2 class="section__title">Students are viewing</h2>
                <span class="section-divider"></span>
            </div><!-- end section-heading -->
            <div class="course-carousel owl-action-styled owl--action-styled mt-30px">
                @foreach ($courses as $course)
                    <div class="card card-item card-preview" data-tooltip-content="#tooltip_content_{{ $course->id }}">
                        <div class="card-image">
                            <a href="{{ route('course_details', ['id' => $course->id, 'slug' => $course->slug]) }}" class="d-block">
                                <img class="card-img-top" src="{{ Storage::url('upload/course/images/'. $course->image) }}" alt="Card image cap">
                            </a>
                            <div class="course-badge-labels">
                                @if($course->best_seller == 1)
                                    <div class="course-badge orange">Bestseller</div>
                                @endif
                                @if($course->highest_rated == 1)
                                    <div class="course-badge green">Highest Rated</div>
                                @endif
                                @if($course->featured == 1)
                                    <div class="course-badge red">Featured</div>
                                @endif
                                @if ($course->discount_price > 0)
                                    <div class="course-badge blue">{{ round(($course->selling_price - $course->discount_price) / $course->selling_price * 100)}}%</div>
                                @else
                                    <div class="course-badge blue">New</div>
                                @endif
                            </div>
                        </div><!-- end card-image -->
                        <div class="card-body" style="padding:1.6rem 2.6rem;">
                            <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $course->course_level }}</h6>
                            <h5 class="card-title"><a href="{{ route('course_details', ['id' => $course->id, 'slug' => $course->slug]) }}">{{ $course->title }}</a></h5>
                            <p class="card-text"><a href="{{ route('instructor_details', $course->instructor->id) }}">{{ $course->instructor->name }}</a></p>
                            <div class="rating-wrap d-flex align-items-center py-2">
                                <div class="review-stars">
                                    @if (round($course->averageReviewScore->average_score, 1) == 0)
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        @elseif (round($course->averageReviewScore->average_score, 1) == 1 || round($course->averageReviewScore->average_score, 1) < 2)
                                        <span class="la la-star"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        @elseif (round($course->averageReviewScore->average_score, 1) == 2 || round($course->averageReviewScore->average_score, 1) < 3)
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        @elseif (round($course->averageReviewScore->average_score, 1) == 3 || round($course->averageReviewScore->average_score, 1) < 4)
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star-o"></span>
                                        <span class="la la-star-o"></span>
                                        @elseif (round($course->averageReviewScore->average_score, 1) == 4 || round($course->averageReviewScore->average_score, 1) < 5)
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star-o"></span>
                                        @elseif (round($course->averageReviewScore->average_score, 1) == 5 || round($course->averageReviewScore->average_score, 1) < 5)
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                        <span class="la la-star"></span>
                                    @endif
                                </div>
                                <span class="rating-total pl-1">({{count($course->reviews) }})</span>
                            </div><!-- end rating-wrap -->
                            <div class="d-flex justify-content-between align-items-center">
                                @if ($course->discount_price > 0)
                                    <p class="card-price text-black font-weight-bold">${{ $course->discount_price }} <span class="before-price font-weight-medium">${{ $course->selling_price }}</span></p>
                                    @else 
                                    <p class="card-price text-black font-weight-bold">${{ $course->selling_price }}</p>
                                @endif
                                @auth
                                <div class="icon-element icon-element-sm shadow-sm cursor-pointer wishlist-icon" title="Add to Wishlist" id="{{ $course->id }}" onclick="addToWishlist(this.id)">
                                    @if (auth()->user()->wishlistCourses->contains($course->id))
                                        <i class="la la-heart icon-red"></i></div>
                                    @else
                                        <i class="la la-heart-o"></i></div>
                                    @endif
                                @else
                                    <div class="icon-element icon-element-sm shadow-sm cursor-pointer wishlist-icon" title="Add to Wishlist" id="{{ $course->id }}" onclick="addToWishlist(this.id)"><i class="la la-heart-o"></i></div>
                                @endauth
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                @endforeach
            </div><!-- end tab-content -->
        </div><!-- end container -->
    </div><!-- end course-wrapper -->
</section><!-- end courses-area -->