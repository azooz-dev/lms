@extends('frontend.dashboard.dashboard_user')

@section('user_dashboard')


@section('title_dashboard')
Dashboard Settings | Easy Learning
@endsection



<div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between mb-5">
    <div class="media media-card align-items-center">
        <div class="media-img media--img media-img-md rounded-full">
            <img class="rounded-full" src="{{ (!empty($user->photo)) ? Storage::url('public/upload/users_images/'. $user->photo) : asset('storage/upload/images.jpg') }}" alt="Student thumbnail image">
        </div>
        <div class="media-body">
            <h2 class="section__title fs-30">Hello, {{ $user->name }}</h2>
            </div><!-- end rating-wrap -->
        </div><!-- end media-body -->
    </div><!-- end media -->
</div><!-- end breadcrumb-content -->
<div class="section-block mb-5"></div>
            <div class="dashboard-heading mb-5">
                <h3 class="fs-22 font-weight-semi-bold">Settings</h3>
            </div>
            <ul class="nav nav-tabs generic-tab pb-30px" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="edit-profile-tab" data-toggle="tab" href="#edit-profile" role="tab" aria-controls="edit-profile" aria-selected="false">
                        Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="true">
                        Password
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="change-email-tab" data-toggle="tab" href="#change-email" role="tab" aria-controls="change-email" aria-selected="false">
                        Change Email
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="false">
                        Account
                    </a>
                </li> --}}
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                    <div class="setting-body">
                        <h3 class="fs-17 font-weight-semi-bold pb-4">Edit Profile</h3>
                        <form method="POST" action="{{ route('user.update_profile', $user->id) }}" enctype="multipart/form-data" class="row pt-40px">
                            @csrf
                            @method('PUT')
                        <div class="media media-card align-items-center">
                            <div class="media-img media-img-lg mr-4 bg-gray">
                                <img class="mr-3" src="{{ (!empty($user->photo)) ? Storage::url('public/upload/users_images/'. $user->photo) : asset('storage/upload/images.jpg') }}" alt="avatar image">
                            </div>

                            <div class="media-body">
                                <div class="file-upload-wrap file-upload-wrap-2">
                                    <input type="file" name="photo" class="multi file-upload-input with-preview @error('photo') is-invalid @enderror" id="photo">
                                    <span class="file-upload-text"><i class="la la-photo mr-2"></i>Upload a Photo</span>
                                    @error('photo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div><!-- file-upload-wrap -->
                                <p class="fs-14">Max file size is 5MB, Minimum dimension: 200x200 And Suitable files are .jpg & .png</p>
                            </div>
                        </div><!-- end media -->

                            <div class="input-box col-lg-6">
                                <label class="label-text">Name</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ $user->name }}" placeholder="Enter your full name">
                                    <span class="la la-user input-icon"></span>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-6">
                                <label class="label-text">Usernmae</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('username') is-invalid @enderror" type="text" name="username" id="username" value="{{ $user->username }}">
                                    <span class="la la-user input-icon"></span>
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-6">
                                <label class="label-text">Address</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('address') is-invalid @enderror" type="text" id="address" name="address" value="{{ $user->address }}">
                                    <span class="fas fa-map-marker-alt input-icon"></span>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-6">
                                <label class="label-text">Phone Number</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('phone') is-invalid @enderror" type="text" id="phone" name="phone" value="{{ $user->phone }}">
                                    <span class="la la-phone input-icon"></span>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-12">
                                <label class="label-text">Bio</label>
                                <div class="form-group">
                                    <textarea class="form-control form--control user-text-editor pl-3 @error('bio') is-invalid @enderror" name="bio" id="bio">{{ $user->bio }}</textarea>
                                    @error('bio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-12 py-3">
                                <button type="submit" class="btn theme-btn">Save Changes</button>
                            </div><!-- end input-box -->
                        </form>
                    </div><!-- end setting-body -->
                </div><!-- end tab-pane -->

                
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <div class="setting-body">
                        <h3 class="fs-17 font-weight-semi-bold pb-4">Change Password</h3>
                        <form method="POST" action="{{ route('user.change_password', $user->id) }}" class="row">
                            @csrf
                            @method('PUT')
                            <div class="input-box col-lg-4">
                                <label class="label-text">Old Password</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('old_password') is-invalid @enderror" type="password" id="old_password" name="old_password" placeholder="Old Password">
                                    <span class="la la-lock input-icon"></span>
                                    @error('old_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-4">
                                <label class="label-text">New Password</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('new_password') is-invalid @enderror" type="password" name="new_password" id="new_password" placeholder="New Password">
                                    <span class="la la-lock input-icon"></span>
                                    @error('new_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-4">
                                <label class="label-text">Confirm New Password</label>
                                <div class="form-group">
                                    <input class="form-control form--control" type="password" id="password_confirmation" name="new_password_confirmation" placeholder="Confirm New Password">
                                    <span class="la la-lock input-icon"></span>
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-12 py-2">
                                <button type="submit" class="btn theme-btn">Change Password</button>
                            </div><!-- end input-box -->
                        </form>
                        {{-- <form method="post" class="pt-5 mt-5 border-top border-top-gray">
                            <h3 class="fs-17 font-weight-semi-bold pb-1">Forgot Password then Recover Password</h3>
                            <p class="pb-4">Enter the email of your account to reset password. Then you will receive a link to email
                                to reset the password. If you have any issue about reset password
                                <a href="contact.html" class="text-color">contact us</a></p>
                            <div class="input-box">
                                <label class="label-text">Email Address</label>
                                <div class="form-group">
                                    <input class="form-control form--control" type="email" name="email" placeholder="Enter email address">
                                    <span class="la la-envelope input-icon"></span>
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box py-2">
                                <button class="btn theme-btn">Recover Password</button>
                            </div><!-- end input-box -->
                        </form> --}}
                    </div><!-- end setting-body -->
                </div><!-- end tab-pane -->
                <div class="tab-pane fade" id="change-email" role="tabpanel" aria-labelledby="change-email-tab">
                    <div class="setting-body">
                        <h3 class="fs-17 font-weight-semi-bold pb-4">Change Email</h3>
                        <form method="POST" action="{{ route('user.change_email', $user->id) }}" class="row">
                            @csrf
                            @method('PUT')
                            <div class="input-box col-lg-4">
                                <label class="label-text">Old Email</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('old_email') is-invalid @enderror" type="email" id="old_email" name="old_email" placeholder="Old Email">
                                    <span class="la la-envelope input-icon"></span>
                                    @error('old_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-4">
                                <label class="label-text">New Email</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('new_email') is-invalid @enderror" type="email" id="new_email" name="new_email" placeholder="New Email">
                                    <span class="la la-envelope input-icon"></span>
                                    @error('new_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-4">
                                <label class="label-text">Confirm New Email</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('email_confirmation') is-invalid @enderror" type="email" id="email_confirmation" name="email_confirmation" placeholder="Confirm New Email">
                                    <span class="la la-envelope input-icon"></span>
                                    @error('email_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-12 py-2">
                                <button type="submit" class="btn theme-btn">Change Email</button>
                            </div><!-- end input-box -->
                        </form>
                    </div><!-- end setting-body -->
                </div><!-- end tab-pane -->
                {{-- <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                    <div class="setting-body">
                        <h3 class="fs-17 font-weight-semi-bold pb-4">My Account</h3>
                        <form method="post" class="mb-40px">
                            <div class="custom-control-wrap d-flex flex-wrap align-items-center">
                                <div class="custom-control custom-radio pl-0 flex-shrink-0 mr-3 mb-2">
                                    <input type="radio" class="custom-control-input" id="deactivateAccount" name="radio-stacked" required>
                                    <label class="custom-control-label custom--control-label custom--control-label-boxed" for="deactivateAccount">
                                        <span class="font-weight-semi-bold text-black">Deactivate Account</span>
                                    </label>
                                </div>
                                <button class="btn theme-btn mb-2">Deactivate</button>
                            </div><!-- end custom-control-wrap -->
                        </form>
                        <div class="section-block"></div>
                        <div class="danger-zone pt-40px">
                            <h4 class="fs-17 font-weight-semi-bold text-danger">Delete Account Permanently</h4>
                            <p class="pt-1 pb-4"><span class="text-warning">Warning: </span>Once you delete your account, there is no going back. Please be certain.</p>
                            <button class="btn theme-btn" data-toggle="modal" data-target="#deleteModal">Delete my account</button>
                        </div>
                    </div><!-- end setting-body -->
                </div><!-- end tab-pane --> --}}
            </div>

@endsection