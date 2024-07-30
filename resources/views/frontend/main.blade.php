<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="author" content="TechyDevs">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>

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
    <link rel="stylesheet" href="{{ asset('frontend/css/tooltipster.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/plyr.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <!-- end inject -->

    <link rel="stylesheet" href="{{ asset('frontend/fontawosem/all.min.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        .icon-red {
            color: red;
        }
        .swal2-dark {
            --swal2-background-color: #333;
            --swal2-title-color: #fff;
            --swal2-content-color: #fff;
            --swal2-icon-color: #fff;
            --swal2-button-background-color: #444;
            --swal2-button-background-color-hover: #555;
            --swal2-button-background-color-active: #666;
            --swal2-button-text-color: #fff;
            --swal2-button-text-color-hover: #fff;
            --swal2-button-text-color-active: #fff;
        }
    </style>

    
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
@include('frontend.body.header', [
    'site' => $site
])
<!--======================================
        END HEADER AREA
======================================-->
@yield('content')
<!-- ================================
        END FOOTER AREA
================================= -->
@include('frontend.body.footer', [
    'site' => $site
])
<!-- ================================
        END FOOTER AREA
================================= -->


<!-- start scroll top -->
<div id="scroll-top">
    <i class="la la-arrow-up" title="Go top"></i>
</div>
<!-- end scroll top -->

<!-- template js files -->
<script src="{{ asset('frontend/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/js/isotope.js') }}"></script>
<script src="{{ asset('frontend/js/waypoint.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('frontend/js/fancybox.js') }}"></script>
<script src="{{ asset('frontend/js/datedropper.min.js') }}"></script>
<script src="{{ asset('frontend/js/emojionearea.min.js') }}"></script>
<script src="{{ asset('frontend/js/tooltipster.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.lazy.min.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<script src="{{ asset('frontend/fontawosem/all.min.js') }}"></script>
<script src="{{ asset('frontend/js/plyr.js') }}"></script>
{{-- <script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
<script src="https://mediafiles.botpress.cloud/a420226c-6d91-408b-888d-65f32e65d7ba/webchat/config.js" defer></script> --}}
<script>
    var player = new Plyr('#player');
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- Start Add To Mini Cart -->
<script>
    function addToCart(courseId) {
        const url = '{{ route('cart.store', ['id' => ':id']) }}'.replace(':id', courseId);
        fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Determine the theme
            const theme = localStorage.getItem('theme') || document.body.className;
            const isDarkMode = theme === 'dark';

            // Start Message 
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                    if (isDarkMode) {
                        // Dark mode styles
                        toast.style.backgroundColor = '#333';
                        toast.style.color = '#fff';
                    } else {
                        // Light mode styles (optional, as SweetAlert2 defaults to light mode)
                        toast.style.backgroundColor = '#fff';
                        toast.style.color = '#000';
                    }
                }
            });

            if (data.success) {
                // Custom success message
                Toast.fire({
                    icon: 'success',
                    title: data.success
                });
            } else {
                // Custom error message
                Toast.fire({
                    icon: 'error',
                    title: data.error
                });
            }

            // End Message   
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            showMiniCart();
        });
    }
</script>
<!-- End Add To Mini Cart -->

<!-- Start Change The  Wishlist Icon-->
@auth
<script>
    document.querySelectorAll('.wishlist-icon').forEach(function(iconElement) {
        iconElement.addEventListener('click', function() {
            var icon = iconElement.querySelector('i');
            if (icon.classList.contains('la-heart-o')) {
                icon.classList.remove('la-heart-o');
                icon.classList.add('la-heart', 'icon-red');
            } else {
                icon.classList.remove('la-heart', 'icon-red');
                icon.classList.add('la-heart-o');
            }
        });
    });
</script>
@endauth
<!-- End Change The  Wishlist Icon-->

<!-- Start Change The  Wishlist Icon-->
@auth
<script>
    document.querySelector('.wish-btn').addEventListener('click', function() {
        var icon = this.querySelector('i');
        
        if (icon.classList.contains('la-heart-o')) {
            icon.classList.remove('la-heart-o');
            icon.classList.add('la-heart', 'icon-red');
        } else {
            icon.classList.remove('la-heart', 'icon-red');
            icon.classList.add('la-heart-o');
            
        }
    });
</script>
@endauth
<!-- End Change The  Wishlist Icon-->


<!-- Start Show Mini Cart And Remove From Mini Cart -->
<script>
    // Start Show Mini Cart Content
    function showMiniCart() {
        var cart = '';
        const url = '{{ route('mini_cart') }}';
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.cartContent && typeof data.cartContent === 'object') {
                const cartContent = Object.values(data.cartContent);

                var total_discount = 0;
                cartContent.forEach(course => {
                    cart += ` <li class="media media-card">
                                                <a href="/course/details/${course.id}/${course.options.slug}" class="media-img">
                                                    <img src="${course.options.image}" alt="Cart image">
                                                </a>
                                                <div class="media-body">
                                                    <h5><a href="/course/details/${course.id}/${course.options.slug}">${course.name}</a></h5>
                                                    <span class="d-block lh-18 py-1">${course.options.instructor_name}</span>
                                                    ${course.options.selling_price ? `<p class="text-black font-weight-semi-bold lh-18">$${parseFloat(course.price).toFixed(2)} <span class="before-price fs-14">$${course.options.selling_price}</span></p>` : `<p class="text-black font-weight-semi-bold lh-18">$${parseFloat(course.price).toFixed(2)}</p>`}
                                                </div>
                                                <a type="submit" id="${course.rowId}" onclick="removeCourseMiniCart(this.id)"><i class="la la-times"></i></a>
                                            </li>
                    `;
                    total_discount += parseFloat(course.options.selling_price);
                });
                document.getElementById('mini-cart').innerHTML = cart;
                document.getElementById('cart-total').innerHTML = `${'$' + data.cartTotal}`;
                document.getElementById('before-price').innerHTML = `${'$' + total_discount}`;
                document.getElementById('mini-cart-count').innerHTML = data.cartCount;
            }
        })

    }
    // End Show Mini Cart Content

    showMiniCart();


    // Start Remove Course From Mini Cart 
    function removeCourseMiniCart(rowId) {
        // Determine the theme outside of the fetch request
    const theme = localStorage.getItem('theme') || document.body.className;
    const isDarkMode = theme === 'dark';

    // Define the Toast constant outside of the fetch request
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
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
        const url = '{{ route('mini_cart_delete', ':id') }}'.replace(':id', rowId);
        fetch(url, {
            method: 'Delete',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            showMiniCart();
            // Check if the response contains an error message
            if (data.error) {
                // Throw an error with the error message from the response
                throw new Error(data.error);
            }
            // If there's no error, proceed with the success message
            Toast.fire({
                icon: 'success',
                title: data.success
            });
        })
        .catch(error => console.log(error));
    }
    // End Remove Course From Mini Cart
</script>
<!-- End Show Mini Cart And Remove From Mini Cart -->

<!-- Start Show Cart And Remove From Cart -->
<script>
    function myCart() {
        // Determine the theme outside of the fetch request
        const theme = localStorage.getItem('theme') || document.body.className;
        const isDarkMode = theme === 'dark';

        // Define the Toast constant outside of the fetch request
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
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

        const url = '{{ route('my_cart_content')}}';
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            var cart = '';
            var subtotal = 0;
            // Check if cartContent is an object before using Object.values
            if (typeof data.cartContent === 'object' && data.cartContent !== null) {
                Object.values(data.cartContent).forEach(course => {
                    cart += `<tr>
                                <th scope="row">
                                    <div class="media media-card">
                                        <a href="/course/details/${course.id}/${course.options.slug}" class="media-img mr-0">
                                            <img src="${course.options.image}" alt="Cart image">
                                        </a>
                                    </div>
                                </th>
                                <td>
                                    <a href="/course/details/${course.id}/${course.options.slug}" class="text-black font-weight-semi-bold">${course.name}</a>
                                    <p class="fs-14 text-gray lh-20">By <a href="/instructor/details/${course.options.instructor_id}" class="text-color hover-underline">${course.options.instructor}</a></p>
                                </td>
                                <td>
                                    <ul class="generic-list-item font-weight-semi-bold">
                                        ${course.options.selling_price ? `<li class="text-black lh-18">$${parseFloat(course.price).toFixed(2)}</li> <li class="before-price lh-18">$${course.options.selling_price}</li>` : `<li class="text-black lh-18">$${parseFloat(course.price).toFixed(2)}</li>`}
                                    </ul>
                                </td>
                                <td>
                                    <button type="button" class="icon-element icon-element-xs shadow-sm border-0" data-toggle="tooltip" data-placement="top" title="Remove" id="${course.rowId}" onclick="removeCourseMyCart(this.id)">
                                        <i class="la la-times"></i>
                                    </button>
                                </td>
                            </tr>`;
                            subtotal += parseFloat(course.subtotal); ;
                });
            } else {
                console.error('cartContent is not an object:', data.cartContent);
            }
            document.getElementById('tbody').innerHTML = cart;
            document.getElementById('subtotal').innerHTML = `$${subtotal.toFixed(2)}`;
            document.getElementById('total').innerHTML = `$${data.cartTotal}`;

        })
    }

    // Start Remove Course From My Cart
    function removeCourseMyCart(rowId) {
        // Determine the theme outside of the fetch request
        const theme = localStorage.getItem('theme') || document.body.className;
        const isDarkMode = theme === 'dark';

        // Define the Toast constant outside of the fetch request
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
        const url = '/remove/course/cart/' + rowId;
            fetch(url, {
                method: 'Delete',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                var couponBtn = document.getElementById('applyCoupon');
                var successIcon = document.getElementById('successIcon');
                var divMessage = document.getElementById('divMessage');
                const couponName = document.getElementById('coupon_name');
                if (couponBtn.innerHTML == 'Remove') {
                    successIcon.style.display = 'none';
                    divMessage.innerHTML = '';
                    couponName.value = '';
                    couponBtn.innerHTML = "Apply Code";
                }

                showMiniCart();
                myCart();
                cartCalc();

                // Check if the response contains an error message
                if (data.error) {
                    // Throw an error with the error message from the response
                    throw new Error(data.error);
                }
                // If there's no error, proceed with the success message
                Toast.fire({
                    icon: 'success',
                    title: data.success
                });

            })
            .catch(error => console.log(error));
    }
    // End Remove Course From My Cart

    myCart();
</script>
<!-- Remove Show Cart And Remove From Cart -->

<!-- Start Apply Coupon -->
<script>

    cartCalc()
    /**
     * Applies a coupon to the cart.
     * Fetches the server for applying the coupon and updates the UI accordingly.
     */
    function applyCourseCoupon(courseId = null, instructorId = null) {
        // Get the necessary elements from the UI
        var couponBtn = document.getElementById('applyCoupon');
        var successIcon = document.getElementById('successIcon');
        var errorIcon = document.getElementById('errorIcon');
        var divMessage = document.getElementById('divMessage');

        // Get the coupon name from the UI
        const couponName = document.getElementById('coupon_name');
    

        if (couponBtn.innerHTML == 'Remove') {
            // Determine the theme outside of the fetch request
            const theme = localStorage.getItem('theme') || document.body.className;
            const isDarkMode = theme === 'dark';

            // Define the Toast constant outside of the fetch request
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

            const url = '{{ route('remove_coupon') }}';

            fetch(url, {
                method: 'Delete',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }

            })
            .then(response => response.json())
            .then(data => {
                cartCalc();

                Toast.fire({
                    icon: 'success',
                    title: data.success
                });

                successIcon.style.display = 'none';
                divMessage.innerHTML = '';
                couponName.value = '';
                couponBtn.innerHTML = "Apply Code";
            })
        } else {
            // Define the URL for the coupon application
            const url = '{{ route('apply_coupon') }}';

            // Send a POST request to the server with the coupon name
            fetch(`${url}?id=${courseId}&instructor=${instructorId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    coupon_name: couponName.value
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.error) {
                    // Handle error response from the server
                    errorIcon.style.display = 'block';
                    successIcon.style.display = 'none';
                    divMessage.innerHTML = '';
    
                    // Create a span element to display the error message
                    var errorMessage = document.createElement('span');
                    errorMessage.className = 'text-danger';
                    errorMessage.innerHTML = data.error;
                    divMessage.appendChild(errorMessage);

                    cartCalc();
                } else {
                    // Handle success response from the server
                    successIcon.style.display = 'block';
                    errorIcon.style.display = 'none';
    
                    divMessage.innerHTML = '';
                    // Create a span element to display the success message
                    var successMessage = document.createElement('span');
                    successMessage.className = 'text-success';
                    successMessage.innerHTML = data.message;
                    divMessage.appendChild(successMessage);
    
                    cartCalc();
    
                    couponBtn.innerHTML = 'Remove';
                }
            })
        }
    }


    function applyCoupon() {
        // Get the necessary elements from the UI
        var couponBtn = document.getElementById('applyCoupon');
        var successIcon = document.getElementById('successIcon');
        var errorIcon = document.getElementById('errorIcon');
        var divMessage = document.getElementById('divMessage');

        // Get the coupon name from the UI
        const couponName = document.getElementById('coupon_name');
    

        if (couponBtn.innerHTML == 'Remove') {
            // Determine the theme outside of the fetch request
            const theme = localStorage.getItem('theme') || document.body.className;
            const isDarkMode = theme === 'dark';

            // Define the Toast constant outside of the fetch request
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

            const url = '{{ route('remove_coupon') }}';

            fetch(url, {
                method: 'Delete',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                cartCalc();

                Toast.fire({
                    icon: 'success',
                    title: data.success
                });

                successIcon.style.display = 'none';
                divMessage.innerHTML = '';
                couponName.value = '';
                couponBtn.innerHTML = "Apply Code";
            })
        } else {
            // Define the URL for the coupon application
            const url = '{{ route('apply_coupon') }}';

            // Send a POST request to the server with the coupon name
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    coupon_name: couponName.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    // Handle error response from the server
                    errorIcon.style.display = 'block';
                    successIcon.style.display = 'none';
                    divMessage.innerHTML = '';
    
                    // Create a span element to display the error message
                    var errorMessage = document.createElement('span');
                    errorMessage.className = 'text-danger';
                    errorMessage.innerHTML = data.error;
                    divMessage.appendChild(errorMessage);
    
                    cartCalc();
    
                } else {
                    // Handle success response from the server
                    successIcon.style.display = 'block';
                    errorIcon.style.display = 'none';
    
                    divMessage.innerHTML = '';
                    // Create a span element to display the success message
                    var successMessage = document.createElement('span');
                    successMessage.className = 'text-success';
                    successMessage.innerHTML = data.message;
                    divMessage.appendChild(successMessage);
    
                    cartCalc();
    
                    couponBtn.innerHTML = 'Remove';
    
                }
            })
        }
    }


    /**
     * Fetches cart calculation data from the server and updates the cart totals section.
     * If the cart is empty, it displays the total amount.
     * If the cart is not empty, it displays the subtotal, coupon name (if applicable),
     * discount amount (if applicable), and total amount.
     */
    function cartCalc() {
        const cartTotal = document.getElementById('cartTotal');

        const url = '{{ route('cart_calculation') }}';

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            let content;

            if (data.total) {
                // If the cart is empty, display the total amount
                content = `
                    <h3 class="fs-18 font-weight-bold pb-3">Cart Totals</h3>
                    <div class="divider"><span></span></div>
                    <ul class="generic-list-item pb-4">
                        <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                            <span class="text-black">Subtotal:</span>
                            <span id="subtotal">$${data.total}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                            <span class="text-black">Total:</span>
                            <span id="total">$${data.total}</span>
                        </li>
                    </ul>
                    <a href="/checkout" class="btn theme-btn w-100">Checkout <i class="la la-arrow-right icon ml-1"></i></a>`;
            } else {
                // If the cart is not empty, display the subtotal, coupon name (if applicable),
                // discount amount (if applicable), and total amount
                content = `
                    <h3 class="fs-18 font-weight-bold pb-3">Cart Totals</h3>
                    <div class="divider"><span></span></div>
                    <ul class="generic-list-item pb-4">
                        <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                            <span class="text-black">Subtotal:</span>
                            <span id="subtotal">$${data.subTotal}</span>
                        </li>
                        ${data.coupon_name ? `<li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                            <span class="text-black">Coupon Name:</span>
                            <span id="subtotal">${data.coupon_name}</span>
                        </li>` : ''}
                        ${data.discount_amount ? `<li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                            <span class="text-black">Discount Amount:</span>
                            <span id="subtotal">$${data.discount_amount}</span>
                        </li>` : ''}
                        <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                            <span class="text-black">Total:</span>
                            <span id="total">$${data.total_amount}</span>
                        </li>
                    </ul>
                    <a href="/checkout" class="btn theme-btn w-100">Checkout <i class="la la-arrow-right icon ml-1"></i></a>`;
            }

            cartTotal.innerHTML = content;
        })
    }
</script>
<!-- End Apply Coupon -->


<script>
    function buyCourse(courseId) {
        const url = '{{ route('buy.course', ['id' => ':id']) }}'.replace(':id', courseId);
        fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Determine the theme
            const theme = localStorage.getItem('theme') || document.body.className;
            const isDarkMode = theme === 'dark';

            // Start Message 
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                    if (isDarkMode) {
                        // Dark mode styles
                        toast.style.backgroundColor = '#333';
                        toast.style.color = '#fff';
                    } else {
                        // Light mode styles (optional, as SweetAlert2 defaults to light mode)
                        toast.style.backgroundColor = '#fff';
                        toast.style.color = '#000';
                    }
                }
            });

            if (data.success) {
                window.location.href = '/checkout';
            }
            // End Message   
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            showMiniCart();
        });
    }
</script>


</body>
</html>