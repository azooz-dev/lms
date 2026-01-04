<div class="off-canvas-menu-close dashboard-menu-close icon-element icon-element-sm shadow-sm" data-toggle="tooltip" data-placement="left" title="Close menu">
    <i class="la la-times"></i>
</div><!-- end off-canvas-menu-close -->
<div class="logo-box px-4">
    <a href="{{ route('index') }}" class="logo"><img src="{{ asset('frontend/images/logo.png') }}" alt="logo"></a>
</div>
<ul class="generic-list-item off-canvas-menu-list off--canvas-menu-list pt-35px">
    <li class="page-active"><a href="{{ route('dashboard', $user->id) }}"><svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z"/></svg> Dashboard</a></li>
    <li><a href="{{ route('user.profile', $user->id) }}"><svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 5.9c1.16 0 2.1.94 2.1 2.1s-.94 2.1-2.1 2.1S9.9 9.16 9.9 8s.94-2.1 2.1-2.1m0 9c2.97 0 6.1 1.46 6.1 2.1v1.1H5.9V17c0-.64 3.13-2.1 6.1-2.1M12 4C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/></svg> My Profile</a></li>
    <li><a href="{{ route('user.my_course', $user->id) }}"><svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M8 16h8v2H8zm0-4h8v2H8zm6-10H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg> My Courses</a></li>
    <li><a href="{{ route('user.wishlist') }}"><i class="la la-heart-o mr-1" style="font-size: 16px; padding-right: 6px;"></i> My Wishlist <span class="badge badge-info p-1 ml-2" id="wishlist-count">2</span></a></li>
</ul>   