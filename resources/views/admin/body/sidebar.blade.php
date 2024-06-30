
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Admin</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        

        <li class="menu-label">UI Elements</li>
        <li>
            <a href="widgets.html">
                <div class="parent-icon"><i class='bx bx-cookie'></i>
                </div>
                <div class="menu-title">Widgets</div>
            </a>
        </li>

        @can('category.menu')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="fas fa-list" style="font-size: 19px;"></i>
                        <!-- SVG content -->
                    </svg>
                    </div>
                    <div class="menu-title">Categories</div>
                </a>
                <ul>
                    @can('category.all')
                        <li> <a href="{{ route('admin.all_categories') }}"><i class="bx bx-radio-circle"></i>All Categories</a>
                        </li>
                    @endcan
                    @can('subcategory.all')
                        <li> <a href="{{ route('admin.all_subCategories') }}"><i class="bx bx-radio-circle"></i>All Subcategories</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('instructor.menu')
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-user-check'></i>
                    </div>
                    <div class="menu-title">Instructors</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_instructors') }}"><i class='bx bx-radio-circle'></i>All Instructors</a>
                    </li>
                </ul>
            </li>
        @endcan

            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><svg class="svg-icon" style="width: 20px; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1231 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M1183.808012 1023.985196a47.254023 47.254023 0 0 1-25.373871-7.401946c-156.921253-99.556172-359.601336-99.556172-516.478177 0a47.372454 47.372454 0 0 1-72.761128-39.970508v-846.782611a47.372454 47.372454 0 0 1 21.983779-39.970508 576.715213 576.715213 0 0 1 618.018071 0 47.372454 47.372454 0 0 1 21.98378 39.970508v846.782611a47.372454 47.372454 0 0 1-47.372454 47.372454zM663.939744 156.595568v740.845961a576.81884 576.81884 0 0 1 472.495814 0V156.595568c-146.055197-81.998757-326.440618-81.998757-472.495814 0z"  /><path d="M614.62798 1023.985196a47.268827 47.268827 0 0 1-25.388675-7.401946c-156.921253-99.556172-359.601336-99.556172-516.478177 0A47.372454 47.372454 0 0 1 0 976.612742v-846.782611a47.372454 47.372454 0 0 1 21.998583-39.970508 576.700409 576.700409 0 0 1 618.003267 0 47.372454 47.372454 0 0 1 21.998584 39.970508v846.782611a47.372454 47.372454 0 0 1-47.372454 47.372454zM330.941001 846.841827a575.723352 575.723352 0 0 1 236.25531 50.599702V156.595568c-146.07-82.028364-326.440618-82.028364-472.510619 0v740.845961a575.723352 575.723352 0 0 1 236.255309-50.599702z"  /><path d="M421.244741 455.367712h-207.254485a47.372454 47.372454 0 1 1 0-94.744908h207.254485a47.372454 47.372454 0 0 1 0 94.744908zM421.244741 666.574836h-207.254485a47.372454 47.372454 0 0 1 0-94.744907h207.254485a47.372454 47.372454 0 0 1 0 94.744907zM987.774877 397.899004a47.372454 47.372454 0 0 1-26.647005-8.230964l-64.826243-44.174813-64.841046 44.174813a47.372454 47.372454 0 0 1-74.019459-39.14149V95.573925a47.372454 47.372454 0 1 1 94.744908 0v165.359472l17.453788-11.843113a47.372454 47.372454 0 0 1 53.294011 0l17.453788 11.843113V95.573925a47.372454 47.372454 0 1 1 94.744908 0v254.952625a47.372454 47.372454 0 0 1-47.372454 47.372454z"  /></svg>
                    </div>
                    <div class="menu-title">Courses</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_courses') }}"><i class='bx bx-radio-circle'></i>All Courses</a>
                    </li>
                </ul>
            </li>

        @can('coupon.all')
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="fas fa-tag" style="font-size: 20px;"></i>
                    </div>
                    <div class="menu-title">Coupons</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_coupons') }}"><i class='bx bx-radio-circle'></i>All Coupons</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('order.menu')
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-shopping-bag" style="font-size: 22px;"></i>
                    </div>
                    <div class="menu-title">Orders</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.pending_order') }}"><i class='bx bx-radio-circle'></i>Pending Orders</a>
                    </li>

                    <li> <a href="{{ route('admin.confirm_order') }}"><i class='bx bx-radio-circle'></i>Confirm Orders</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('report.menu')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-file' style="font-size: 24px;"></i>
                    </div>
                    <div class="menu-title">Reports</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_reports') }}"><i class='bx bx-radio-circle'></i>All Reports</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('review.menu')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-chat' style="font-size: 22px;"></i>
                    </div>
                    <div class="menu-title">Reviews</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.pending_reviews') }}"><i class='bx bx-radio-circle'></i>Pending Reviews</a>
                    </li>
                    <li> <a href="{{ route('admin.active_reviews') }}"><i class='bx bx-radio-circle'></i>Active Reviews</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('blog.menu')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='fas fa-pen' style="font-size: 20px;"></i>
                    </div>
                    <div class="menu-title">Blogs</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_blog_category') }}"><i class='bx bx-radio-circle'></i>Blog Categories</a>
                    </li>
                    <li> <a href="{{ route('admin.all_posts') }}"><i class='bx bx-radio-circle'></i>Blog Posts</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('all.user.menu')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-group' style="font-size: 22px;"></i>
                    </div>
                    <div class="menu-title">Users</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_users') }}"><i class='bx bx-radio-circle'></i>All Users</a>
                    </li>
                    <li> <a href="{{ route('admin.Instructors') }}"><i class='bx bx-radio-circle'></i>All Instructors</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('setting.menu')
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="fas fa-cog" style="font-size: 20px;"></i>
                    </div>
                    <div class="menu-title">Settings</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.site_setting') }}"><i class='bx bx-radio-circle'></i>Site Settings</a>
                    </li>

                    <li> <a href="{{ route('admin.smtp_setting') }}"><i class='bx bx-radio-circle'></i>Smtp Settings</a>
                    </li>
                </ul>
            </li>
        @endcan


        @can('rolepermission.menu')
            <li class="menu-label">Role & Permissions</li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="fas fa-user-shield" style="font-size: 20px;"></i>
                    </div>
                    <div class="menu-title">Role & Permissions</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_permission') }}"><i class='bx bx-radio-circle'></i>All Permissions</a></li>
                    <li> <a href="{{ route('admin.all_role') }}"><i class='bx bx-radio-circle'></i>All Roles</a></li>
                    <li> <a href="{{ route('admin.all_role_permissions') }}"><i class='bx bx-radio-circle'></i>All Roles In Permissions</a></li>
                </ul>
            </li>
        @endcan

        @can('admin.all')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-user-plus' style="font-size: 24px;"></i>
                    </div>
                    <div class="menu-title">Admin</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.all_admins') }}"><i class='bx bx-radio-circle'></i>All Admin</a>
                    </li>
                </ul>
            </li>
        @endcan

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-map-alt"></i>
                </div>
                <div class="menu-title">Maps</div>
            </a>
            <ul>
                <li> <a href="map-google-maps.html"><i class='bx bx-radio-circle'></i>Google Maps</a>
                </li>
                <li> <a href="map-vector-maps.html"><i class='bx bx-radio-circle'></i>Vector Maps</a>
                </li>
            </ul>
        </li>

    </ul>
    <!--end navigation-->
</div>