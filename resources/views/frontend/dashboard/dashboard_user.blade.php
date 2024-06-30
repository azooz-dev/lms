<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="author" content="TechyDevs">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title_dashboard')</title>

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
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <!-- end inject -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    
	<!-- Toaster Alert --> 
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
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
    @include('frontend.dashboard.body.header', [
        'user' => $user,
    ])
<!--======================================
        END HEADER AREA
======================================-->

<!-- ================================
    START DASHBOARD AREA
================================= -->
<section class="dashboard-area">
    <div class="off-canvas-menu dashboard-off-canvas-menu off--canvas-menu custom-scrollbar-styled pt-20px">

        @include('frontend.dashboard.body.sidebar', [
            'user' => $user,
        ])

    </div><!-- end off-canvas-menu -->
    <div class="dashboard-content-wrap">
        <div class="dashboard-menu-toggler btn theme-btn theme-btn-sm lh-28 theme-btn-transparent mb-4 ml-3">
            <i class="la la-bars mr-1"></i> Dashboard Nav
        </div>
        <div class="container-fluid">
            @yield('user_dashboard')

            @include('frontend.dashboard.body.footer')
        </div><!-- end container-fluid -->
    </div><!-- end dashboard-content-wrap -->
</section><!-- end dashboard-area -->
<!-- ================================
    END DASHBOARD AREA
================================= -->

<!-- start scroll top -->
<div id="scroll-top">
    <i class="la la-arrow-up" title="Go top"></i>
</div>
<!-- end scroll top -->

<!-- Modal -->
<div class="modal fade modal-container" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <span class="la la-exclamation-circle fs-60 text-warning"></span>
                <h4 class="modal-title fs-19 font-weight-semi-bold pt-2 pb-1" id="deleteModalTitle">Your account will be deleted permanently!</h4>
                <p>Are you sure you want to delete your account?</p>
                <div class="btn-box pt-4">
                    <button type="button" class="btn font-weight-medium mr-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn theme-btn theme-btn-sm lh-30">Ok, Delete</button>
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
<script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('frontend/js/fancybox.js') }}"></script>
<script src="{{ asset('frontend/js/chart.js') }}"></script>
<script src="{{ asset('frontend/js/doughnut-chart.js') }}"></script>
<script src="{{ asset('frontend/js/bar-chart.js') }}"></script>
<script src="{{ asset('frontend/js/line-chart.js') }}"></script>
<script src="{{ asset('frontend/js/datedropper.min.js') }}"></script>
<script src="{{ asset('frontend/js/emojionearea.min.js') }}"></script>
<script src="{{ asset('frontend/js/animated-skills.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.MultiFile.min.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<!-- Toaster Alert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var darkModeBtn = document.querySelector('.dark-mode-btn');
        var lightModeBtn = document.querySelector('.light-mode-btn');
        var body = document.body;
    
        // Function to toggle dark mode
        function toggleDarkMode(isDarkMode) {
            if (isDarkMode) {
                body.classList.add('dark-theme');
                body.classList.remove('light-theme');
            } else {
                body.classList.add('light-theme');
                body.classList.remove('dark-theme');
            }
            // Store the user's preference in local storage
            localStorage.setItem('darkMode', isDarkMode);
        }
    
        // Check for stored preference and apply
        var isDarkMode = localStorage.getItem('darkMode');
        if (isDarkMode) {
            toggleDarkMode(isDarkMode === 'true');
        }
    
        darkModeBtn.addEventListener('click', function() {
            toggleDarkMode(true);
        });
    
        lightModeBtn.addEventListener('click', function() {
            toggleDarkMode(false);
        });
    });
    </script>


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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<script>
    function wishlist() {
        let row = document.getElementById('wishlist');
        const url = '/all/wishlist/{{ auth()->user()->id }}';
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('wishlist-count').innerHTML = data.courses_count;
            row.innerHTML = '';
            data.courses.forEach(course => {
                let content = `<div class="col-lg-4 responsive-column-half">
                <div class="card card-item">
                    <div class="card-image">
                        <a href="/course/details/${course.id}/${course.slug}" class="d-block">
                            <img class="card-img-top" src="${course.image}" alt="Card image cap">
                        </a>
                        <div class="course-badge-labels">
                            ${course.best_seller == 1 ? `<div class="course-badge orange">Bestseller</div>` : ''}
                            ${course.highest_rated == 1 ? `<div class="course-badge green">Highest Rated</div>` : ''}
                            ${course.featured == 1 ? `<div class="course-badge red">Featured</div>` : ''}
                            ${course.discount_price > 0 ? `<div class="course-badge blue">${course.amount}%</div>` : `<div class="course-badge blue">New</div>`}
                            
                        </div>
                    </div><!-- end card-image -->
                    <div class="card-body" style="padding: 1.6rem 2.6rem;">
                        <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">${course.course_level}</h6>
                        <h5 class="card-title"><a href="/course/details/${course.id}/${course.slug}">${course.name}</a></h5>
                        <p class="card-text"><a href="/instructor/details/${course.instructor.id}">${course.instructor.name}</a></p>
                        <div class="rating-wrap d-flex align-items-center py-2">
                            <div class="review-stars">
                                <span class="rating-number">4.4</span>
                                <span class="la la-star"></span>
                                <span class="la la-star"></span>
                                <span class="la la-star"></span>
                                <span class="la la-star"></span>
                                <span class="la la-star-o"></span>
                            </div>
                            <span class="rating-total pl-1">(20,230)</span>
                        </div><!-- end rating-wrap -->
                        <div class="d-flex justify-content-between align-items-center">
                            ${course.discount_price > 0 ? `<p class="card-price text-black font-weight-bold">$${course.discount_price}<span class="before-price font-weight-medium">$${course.selling_price}</span></p>` : `<p class="card-price text-black font-weight-bold">$${course.selling_price}</p>`}
                            <div class="icon-element icon-element-sm shadow-sm cursor-pointer" data-toggle="tooltip" data-placement="top" title="Remove from Wishlist" id="${course.id}" onclick="removeFromWishlist(this.id)"><i class="la la-heart icon-red"></i></div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
                </div><!-- end col-lg-4 -->
                `;
                 // Convert the string to a DOM element
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = content;
                // Append the first child of the tempDiv to the row
                row.appendChild(tempDiv.firstChild);
            });
        })
    }
wishlist();


function removeFromWishlist(course) {
    const url = `/remove/wishlist/{{ auth()->user()->id }}/${course}`;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }) 
    .then(response => response.json())
    .then(data => {
        wishlist();


        // Determine the theme
        const theme = localStorage.getItem('theme') || document.body.className;
            const isDarkMode = theme === 'dark';

        // Start Message 
        const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                didOpen: () => {
                    if (isDarkMode) {
                        // Dark mode styles
                        Swal.getPopup().style.backgroundColor = '#333';
                        Swal.getTitle().style.color = '#fff';
                        Swal.getContent().style.color = '#fff';
                    } else {
                        // Light mode styles (optional, as SweetAlert2 defaults to light mode)
                        Swal.getPopup().style.backgroundColor = '#fff';
                        Swal.getTitle().style.color = '#000';
                        Swal.getContent().style.color = '#000';
                    }
                }
            });

            if ($.isEmptyObject(data.error)) {
                // Custom success message
                Toast.fire({
                    icon: 'success',
                    title: data.success
                });
            }

            // End Message  
    })
    .catch(error => {
        // Handle network errors or issues with the fetch request
        Toast.fire({
                icon: 'error',
                title: error.error
            });
    })
}
</script>

</body>
</html>