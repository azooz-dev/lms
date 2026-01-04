
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
            </div>

            <div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                <input class="form-control px-5" disabled type="search" placeholder="Search">
                <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
            </div>


            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                        <a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
                        </a>
                    </li>
                    <li class="nav-item dark-mode d-none d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;"><i id="mode-icon" class='bx bx-moon'></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ (!empty(Auth::user()->photo)) ? Storage::url('public/upload/instructor_images/'. Auth::user()->photo) : asset('storage/upload/images.jpg') }}" class="user-img" alt="user avatar" width="200px">
                    <div class="user-info">
                        <p class="user-name mb-0">{{ Auth::user()->name }}</p>
                        <p class="designattion mb-0">{{ Auth::user()->email }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.profile') }}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.change_password') }}"><i class='bx bx-lock-open' style="font-size: 19px;"></i><span>Change Password</span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.logout') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>


<script>
    function markNotificationRead(notificationId) {
        const url = '{{ route("mark-notification-read", "id") }}'.replace("id", notificationId);

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('notification-container').innerHTML = '';
            document.getElementById('notification-count').textContent = data.count;
            data.notifications.forEach(notification => {
                let content = `<a class="dropdown-item" href="javascript:;" onclick="markNotificationRead('${notification.id}')">
                                    <div class="d-flex align-items-center">
                                        <div class="notify bg-light-danger text-danger">Or
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">${notification.data.message}
                                                <span class="msg-time float-end">${notification.created_date}</span></h6>
                                            <p class="msg-info">New Order</p>
                                        </div>
                                    </div>
                                </a>`;

                document.getElementById('notification-container').insertAdjacentHTML('beforeend', content);
            });
        })
        .catch(error => console.log(error));
    }
</script>