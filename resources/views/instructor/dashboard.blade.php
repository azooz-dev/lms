<!DOCTYPE html>
<html lang="en" id="html-tag">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('backend/assets/images/favicon-32x32.png') }}" type="image/png"/>
	<!--plugins-->
	<link href="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>
	<!-- loader-->
	<link href="{{ asset('backend/assets/css/pace.min.css') }}" rel="stylesheet"/>
	<script src="{{ asset('backend/assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('backend/assets/css/upload.css') }}">

	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('backend/assets/css/dark-theme.css') }}"/>
	<link rel="stylesheet" href="{{ asset('backend/assets/css/semi-dark.css') }}"/>
	<link rel="stylesheet" href="{{ asset('backend/assets/css/header-colors.css') }}"/>

	<link rel="stylesheet" href="{{ asset('backend/assets/css/all.min.css') }}">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">`

	
	<!-- DataTable -->
	<link href="{{ asset('backend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />


	{{-- Toaster Alert --}}
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >


	<meta name="csrf-token" content="{{ csrf_token() }}">


	<title>Instructor Dashboard</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
        @include('instructor.body.sidebar')
		<!--end sidebar wrapper -->
		<!--start header -->
        @include('instructor.body.header')
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
            @yield('content')
			<!--end row-->

			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
        @include('instructor.body.footer')
	</div>
	<!--end wrapper-->

	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/chartjs/js/chart.js') }}"></script>
	<script src="{{ asset('backend/assets/js/index.js') }}"></script>
	<!--app JS-->
	<script src="{{ asset('backend/assets/js/app.js') }}"></script>
	
	<!-- Toaster Alert -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	{{-- <script>
		new PerfectScrollbar(".app-container")
	</script> --}}
	<script src="{{ asset('backend/assets/js/all.min.js') }}"></script>

	
	<script>
		new PerfectScrollbar('.chat-list');
		new PerfectScrollbar('.chat-content');
	</script>



<script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>


	

	
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

<!--Sweet ALert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{ asset('backend/assets/js/code.js') }}"></script>
@auth
<script>
    // Function to toggle theme
    function toggleTheme() {
        const htmlTag = document.getElementById('html-tag');
        const themeToggleIcon = document.getElementById('mode-icon');

        // Check current theme
        let currentTheme = sessionStorage.getItem('theme');
        if (currentTheme === 'dark') {
            // Switch to light theme
            htmlTag.classList.remove('dark-theme');
            htmlTag.classList.add('light-theme');
            sessionStorage.setItem('theme', 'light');
            themeToggleIcon.classList.remove('bx-moon'); // Remove dark theme icon
            themeToggleIcon.classList.add('bx', 'bx-sun'); // Add light theme icon
            updateThemeInSession('light');
        } else {
            // Switch to dark theme
            htmlTag.classList.remove('light-theme');
            htmlTag.classList.add('dark-theme');
            sessionStorage.setItem('theme', 'dark');
            themeToggleIcon.classList.remove('bx-sun'); // Remove light theme icon
            themeToggleIcon.classList.add('bx', 'bx-moon'); // Add dark theme icon
            updateThemeInSession('dark');
        }
    }

    // Function to update theme in session
    function updateThemeInSession(theme) {
        fetch('{{ route('update-theme') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ theme: theme })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Theme updated in session');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    // Function to set theme on page load
    function setThemeOnLoad() {
        const htmlTag = document.getElementById('html-tag');
        const themeToggleIcon = document.getElementById('mode-icon');

        let currentTheme = sessionStorage.getItem('theme');
        if (currentTheme === 'dark') {
            htmlTag.classList.add('dark-theme');
            themeToggleIcon.classList.remove('bx-sun'); // Remove light theme icon
            themeToggleIcon.classList.add('bx', 'bx-sun'); // Add dark theme icon
        } else {
            htmlTag.classList.add('light-theme');
            themeToggleIcon.classList.remove('bx-moon'); // Remove dark theme icon
            themeToggleIcon.classList.add('bx', 'bx-moon'); // Add light theme icon
        }
    }

    // Set theme on page load
    setThemeOnLoad();

    // Add event listener to the theme toggle icon
    document.getElementById('mode-icon').addEventListener('click', toggleTheme);
</script>
@endauth


<script src="{{ asset('backend/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>


<script>
$(document).ready(function() {
	$('#example').DataTable();
});
</script>

</body>

</html>