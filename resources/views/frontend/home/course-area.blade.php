<section class="course-area pb-120px">
    <div class="container">
        <div class="section-heading text-center">
            <h5 class="ribbon ribbon-lg mb-2">Choose your desired courses</h5>
            <h2 class="section__title">The world's largest selection of courses</h2>
            <span class="section-divider"></span>
        </div><!-- end section-heading -->
        <ul class="nav nav-tabs generic-tab justify-content-center pb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="business-tab" data-toggle="tab" href="#business" role="tab" aria-controls="business" aria-selected="true">All</a>
            </li>
            @foreach ($categories as $cateogry)
            <li class="nav-item">
                <a class="nav-link" id="business-tab" data-toggle="tab" href="#business{{ $cateogry->id }}" role="tab" aria-controls="business" aria-selected="true" data-category-id="{{ $cateogry->id }}">{{ $cateogry->category_name }}</a>
            </li>
            @endforeach

        </ul>
    </div><!-- end container -->
    <div class="card-content-wrapper bg-gray pt-50px pb-120px">
        <div class="container">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="business" role="tabpanel" aria-labelledby="business-tab">
                    <div class="row">
                        @foreach ($courses->take(6) as $course)
                        <div class="col-lg-4 responsive-column-half">
                            <div class="card card-item card-preview" data-tooltip-content="#tooltip_content_{{ $course->id }}">
                                <div class="card-image">
                                    <a href="{{ route('course_details', ['id' => $course->id, 'slug' => $course->slug]) }}" class="d-block">
                                        <img class="card-img-top lazy" src="{{ Storage::url('upload/course/images/'. $course->image) }}" data-src="{{ Storage::url('upload/course/images/'. $course->image) }}" alt="Card image cap">
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
                        </div><!-- end col-lg-4 -->
                        @endforeach
                    </div><!-- end row -->
                </div><!-- end tab-pane -->


                @foreach ($categories as $category)
                <div class="tab-pane fade" id="business{{ $category->id }}" role="tabpanel" aria-labelledby="business-tab">
                    <div class="row">
                        @forelse ($category->courses as $course)
                        <div class="col-lg-4 responsive-column-half">
                            <div class="card card-item card-preview" data-tooltip-content="#tooltip_content_{{ $course->id }}">
                                <div class="card-image">
                                    <a href="{{ route('course_details', ['id' => $course->id, 'slug' => $course->slug]) }}" class="d-block">
                                        <img class="card-img-top lazy" src="{{ Storage::url('upload/course/images/'. $course->image) }}" data-src="{{ Storage::url('upload/course/images/'. $course->image)}}" alt="Card image cap">
                                    </a>
                                    <div class="course-badge-labels">
                                        @if($course->best_seller == 1)
                                        <div class="course-badge red">Bestseller</div>
                                        @endif
                                        @if($course->highest_rated == 1)
                                        <div class="course-badge green">Highest Rated</div>
                                        @endif
                                        @if($course->featured == 1)
                                        <div class="course-badge orange">Featured</div>
                                        @endif
                                        @if ($course->discount_price > 0)
                                        <div class="course-badge blue">{{ round(($course->selling_price - $course->discount_price) / $course->selling_price * 100)}}%</div>
                                        @else
                                        <div class="course-badge blue">New</div>
                                        @endif
                                    </div>
                                </div><!-- end card-image -->
                                <div class="card-body">
                                    <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $course->course_level }}</h6>
                                    <h5 class="card-title"><a href="{{ route('course_details', ['id' => $course->id, 'slug' => $course->slug]) }}">{{ $course->name }}</a></h5>
                                    <p class="card-text"><a href="{{ route('instructor_details', $course->instructor->id) }}">{{ $course->instructor->name }}</a></p>
                                    <div class="rating-wrap d-flex align-items-center py-2">
                                        <div class="review-stars">
                                            <span class="rating-number">{{ round($course->averageReviewScore->average_score, 1) }}</span>
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
                                        <span class="rating-total pl-1">({{ count($course->reviews) }})</span>
                                    </div><!-- end rating-wrap -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if ($course->discount_price > 0)
                                        <p class="card-price text-black font-weight-bold">{{ $course->discount_price }} <span class="before-price font-weight-medium">{{ $course->selling_price }}</span></p>
                                        
                                        @else 
                                            <p class="card-price text-black font-weight-bold">{{ $course->selling_price }}</p>
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
                        </div><!-- end col-lg-4 -->
                        @empty
                            <h5 class="text-danger">No Course Found</h5>
                        @endforelse
                    </div><!-- end row -->
                </div><!-- end tab-pane -->
                @endforeach

                
            </div><!-- end tab-content -->
            <div class="more-btn-box mt-4 text-center">
                <a href="course-grid.html" class="btn theme-btn">Browse all Courses <i class="la la-arrow-right icon ml-1"></i></a>
            </div><!-- end more-btn-box -->
        </div><!-- end container -->
    </div><!-- end card-content-wrapper -->
</section><!-- end courses-area -->

@foreach ($courses->take(6) as $course)
<div class="tooltip_templates">
    <div id="tooltip_content_{{ $course->id }}">
        <div class="card card-item">
            <div class="card-body">
                <p class="card-text pb-2">By <a href="teacher-detail.html">{{ $course->instructor->name }}</a></p>
                <h5 class="card-title pb-1"><a href="course-details.html">{{ $course->name }}</a></h5>
                <div class="d-flex align-items-center pb-1">
                    @if ($course->best_seller == 1)
                    <h6 class="ribbon fs-14 mr-2">Bestseller</h6>
                    @else
                    <h6 class="ribbon fs-14 mr-2">New</h6>
                    @endif
                    <p class="text-success fs-14 font-weight-medium">Updated<span class="font-weight-bold pl-1">{{ $course->created_at->format('F Y') }}</span></p>
                </div>
                <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center fs-14">
                    <li>{{ $course->duration }} total hours</li>
                    <li>{{ $course->course_level }}</li>
                </ul>
                <p class="card-text pt-1 fs-14 lh-22">{{ $course->prerequisites }}</p>
                <ul class="generic-list-item fs-14 py-3">
                    @foreach ($course->goals as $goal)
                    <li><i class="la la-check mr-1 text-black"></i>{{ $goal->goal }}</li>
                    @endforeach
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn theme-btn flex-grow-1 mr-3" onclick="addToCart({{ $course->id }})"><i class="la la-shopping-cart mr-1 fs-18"></i> Add to Cart</button>
                </div><!-- end tooltip_templates -->
            </div>
        </div>
    </div>
</div>


@endforeach
