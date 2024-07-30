@extends('frontend.dashboard.dashboard_user')

@section('user_dashboard')


@section('title_dashboard')
{{ Auth::user()->name }} profile | Easy Learning
@endsection


<div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between mb-5">
    <div class="media media-card align-items-center">
        <div class="media-img media--img media-img-md rounded-full">
            <img class="rounded-full" src="{{ (!empty($user->photo)) ? Storage::url('public/upload/users_images/'. $user->photo) : asset('storage/upload/images.jpg') }}" alt="Student thumbnail image">
        </div>
        <div class="media-body">
            <h2 class="section__title fs-30">Hello, {{ Auth::user()->name }}</h2>
            </div><!-- end rating-wrap -->
        </div><!-- end media-body -->
    </div><!-- end media -->
</div><!-- end breadcrumb-content -->
<div class="dashboard-heading mb-5">
    <h3 class="fs-22 font-weight-semi-bold">My Profile</h3>
</div>
<div class="profile-detail mb-5">
    <ul class="generic-list-item generic-list-item-flash">
        <li><span class="profile-name">Registration Date:</span><span class="profile-desc">{{ Auth::user()->created_at->format('D d M Y, h:i:s A') }}</span></li>
        <li><span class="profile-name">Name:</span><span class="profile-desc">{{ Auth::user()->name }}</span></li>
        <li><span class="profile-name">User Name:</span><span class="profile-desc">{{ Auth::user()->username }}</span></li>
        <li><span class="profile-name">Email:</span><span class="profile-desc">{{ Auth::user()->email }}</span></li>
        <li><span class="profile-name">Phone Number:</span><span class="profile-desc">{{ Auth::user()->phone }}</span></li>
        <li><span class="profile-name">Address:</span><span class="profile-desc">{{ Auth::user()->address }}</span></li>
        <li>
            <span class="profile-name">Bio:</span>
            <span class="profile-desc">@if (!empty(Auth::user()->bio)) {!! Auth::user()->bio !!} @else {{ '' }} @endif</span>
        </li>
    </ul>
</div>

@endsection