@extends('frontend.main')

@section('content')

@section('title')
Easy Learning
@endsection

<!--================================
        START HERO AREA
=================================-->
@include('frontend.home.hero-area')
<!--================================
        END HERO AREA
=================================-->

<!--======================================
        START FEATURE AREA
======================================-->
@include('frontend.home.feature-area')
<!--======================================
        END FEATURE AREA
======================================-->

<!--======================================
        START CATEGORY AREA
======================================-->
@include('frontend.home.category-area', [
        'categories' => $categories
])
<!--======================================
        END CATEGORY AREA
======================================-->

<!--======================================
        START COURSE AREA
======================================-->
@include('frontend.home.course-area', [
        'categories' => $categories,
        'courses' => $courses,
])
<!--======================================
        END COURSE AREA
======================================-->

<!--======================================
        START COURSE AREA TWO
======================================-->
@include('frontend.home.course-area-two', [
        'courses' => $coursesFeatured,
])
<!--======================================
        END COURSE AREA TWO
======================================-->

<!-- ================================
        START FUNFACT AREA
================================= -->
@include('frontend.home.funfact-area')
<!-- ================================
        END FUNFACT AREA
================================= -->

<!--======================================
        START CTA AREA
======================================-->
@include('frontend.home.cta-area')
<!--======================================
        END CTA AREA
======================================-->

<!--================================
        START TESTIMONIAL AREA
=================================-->
@include('frontend.home.testimonial-area', [
        'reviews' => $reviews
])
<!--================================
        END TESTIMONIAL AREA
=================================-->

<div class="section-block"></div>

<!--======================================
        START ABOUT AREA
======================================-->
@include('frontend.home.about-area')
<!--======================================
        END ABOUT AREA
======================================-->

<div class="section-block"></div>

<!--======================================
        START REGISTER AREA
======================================-->
@include('frontend.home.register-area')
<!--======================================
        END REGISTER AREA
======================================-->

<div class="section-block"></div>

<!-- ================================
        START CLIENT-LOGO AREA
================================= -->
@include('frontend.home.client-logo-area')
<!-- ================================
        END CLIENT-LOGO AREA
================================= -->

<!-- ================================
        START BLOG AREA
================================= -->
@include('frontend.home.blog-area', [
        'posts' => $posts
])
<!-- ================================
        END BLOG AREA
================================= -->

<!--======================================
        START GET STARTED AREA
======================================-->
@include('frontend.home.get-started-area')
<!-- ================================
        END GET STARTED AREA
================================= -->

<!--======================================
        START SUBSCRIBER AREA
======================================-->
@include('frontend.home.subscriber-area')
<!--======================================
        END SUBSCRIBER AREA
======================================-->

@endsection