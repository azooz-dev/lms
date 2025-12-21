<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\backend\BlogController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\ReportController;
use App\Http\Controllers\backend\ReviewController;
use App\Http\Controllers\backend\RoleController;
use App\Http\Controllers\backend\SettingController;
use App\Http\Controllers\backend\SubCategoryController;
use App\Http\Controllers\backend\UserActiveController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the admin dashboard and management features.
| All routes are prefixed with /admin and require admin role.
|
*/

// Admin Login (public)
Route::get('/admin/login', [AdminController::class, 'login'])
    ->name('admin.login')
    ->middleware(RedirectIfAuthenticated::class);

// Admin Authenticated Routes
Route::middleware(['auth', 'roles:admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard & Profile
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
            Route::get('/chart-data', 'getChartData')->name('admin.chart_data');
            Route::get('/logout', 'logout')->name('admin.logout');
            Route::get('/profile', 'admin_profile')->name('admin.profile');
            Route::put('/update/{update}', 'admin_update')->name('admin.update');
            Route::get('/change_password', 'change_password')->name('admin.change_password');
            Route::put('/update/password/{update}', 'update_password')->name('admin.update_password');

            // Admin Management
            Route::get('/all/admins', 'all_admins')->name('admin.all_admins');
            Route::get('/add/admins', 'add_admins')->name('admin.add_admin');
            Route::post('/admin/store', 'store_admin')->name('admin.admin_store');
            Route::get('/admin/edit/{id}', 'edit_admin')->name('admin.edit_admin');
            Route::put('/admin/update/{id}', 'update_admin')->name('admin.update_admin');
            Route::delete('/admin/delete/{id}', 'delete_admin')->name('admin.admin_destroy');

            // Theme
            Route::post('/update-theme', 'update_theme')->name('update-theme');
            Route::get('/get-theme-preference', 'ThemeController@getThemePreference')->name('get-theme-preference');

            // Instructor Management
            Route::get('/all/instructors', 'all_instructors')->name('admin.all_instructors')->middleware('permission:instructor.menu');
            Route::put('/instructor/{id}/status', 'update_instructor_status')->name('admin.update_instructor_status');

            // Course Management
            Route::get('/all/courses', 'all_courses')->name('admin.all_courses');
            Route::put('/course/{id}/status', 'update_course_status')->name('admin.update_course_status');
            Route::get('/course/details/{id}', 'course_details')->name('admin.course_details');
        });

        // Categories
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/all/categories', 'all_categories')->name('admin.all_categories')->middleware('permission:category.all');
            Route::get('/category/add', 'add_category')->name('admin.add_category')->middleware('permission:category.add');
            Route::post('/category/store', 'store_category')->name('admin.category_store');
            Route::get('/category/{id}/edit', 'edit_category')->name('admin.edit_category')->middleware('permission:category.edit');
            Route::put('/category/update/{update}', 'update_category')->name('admin.update_category');
            Route::delete('/category/delete/{delete}', 'destroy_category')->name('admin.category_destroy')->middleware('permission:category.delete');
        });

        // SubCategories
        Route::controller(SubCategoryController::class)->group(function () {
            Route::get('/all/subCategories', 'all_subCategories')->name('admin.all_subCategories')->middleware('permission:subcategory.all');
            Route::get('/subCategory/add', 'add_subCategory')->name('admin.add_subCategory');
            Route::post('/subCategory/store', 'store_subCategory')->name('admin.subCategory_store');
            Route::get('/subCategory/{id}/edit', 'edit_subCategory')->name('admin.edit_subCategory');
            Route::put('/subCategory/update/{update}', 'update_subCategory')->name('admin.update_subCategory');
            Route::delete('/subCategory/delete/{delete}', 'destroy_subCategory')->name('admin.subCategory_destroy');
        });

        // Coupons
        Route::controller(CouponController::class)->group(function () {
            Route::get('/all/coupons', 'all_coupons')->name('admin.all_coupons')->middleware('permission:coupon.all');
            Route::get('/coupon/add', 'add_coupon')->name('admin.add_coupon')->middleware('permission:coupon.add');
            Route::post('/coupon/store', 'store_coupon')->name('admin.coupon_store');
            Route::get('/coupon/{id}/edit', 'edit_coupon')->name('admin.edit_coupon')->middleware('permission:coupon.edit');
            Route::put('/coupon/update/{id}', 'update_coupon')->name('admin.update_coupon');
            Route::delete('/coupon/delete/{id}', 'destroy_coupon')->name('admin.coupon_destroy')->middleware('permission:coupon.delete');
        });

        // Settings
        Route::controller(SettingController::class)->group(function () {
            Route::get('/smtp/setting', 'smtp_setting')->name('admin.smtp_setting')->middleware('permission:setting.menu');
            Route::put('/smtp/update/{id}', 'smtp_update')->name('admin.update_smtp');
            Route::get('/site/setting', 'site_setting')->name('admin.site_setting');
            Route::put('/site/setting/update/{id}', 'site_setting_update')->name('admin.update_site_setting');
        });

        // Orders
        Route::controller(OrderController::class)->group(function () {
            Route::get('/pending/order', 'pending_order')->name('admin.pending_order')->middleware('permission:order.menu');
            Route::get('/order/details/{id}', 'order_details')->name('admin.order_details');
            Route::put('/order/update/status/{id}', 'update_order_status')->name('admin.update_order_status');
            Route::get('/confirm/order', 'confirm_order')->name('admin.confirm_order')->middleware('permission:order.menu');
        });

        // Reports
        Route::controller(ReportController::class)->group(function () {
            Route::get('/all/reports', 'all_reports')->name('admin.all_reports')->middleware('permission:report.menu');
            Route::post('/date/reports', 'date_reports')->name('admin.date_reports');
        });

        // Reviews
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/pending/reviews', 'pending_reviews')->name('admin.pending_reviews')->middleware('permission:review.menu');
            Route::get('/active/reviews', 'active_reviews')->name('admin.active_reviews');
            Route::put('update/review/status/{id}', 'update_review_status')->name('admin.update_review_status');
        });

        // User Management
        Route::controller(UserActiveController::class)->group(function () {
            Route::get('all/users', 'all_users')->name('admin.all_users')->middleware('permission:all.user.menu');
            Route::get('all/Instructors', 'all_Instructors')->name('admin.Instructors')->middleware('permission:all.user.menu');
        });

        // Blog Categories
        Route::controller(BlogController::class)->group(function () {
            Route::get('all/blog/category', 'all_blog_category')->name('admin.all_blog_category')->middleware('permission:blog.menu');
            Route::post('/store/blog/category', 'store_blog_category')->name('admin.store_blog_category');
            Route::get('/blog/category/edit/{id}', 'blog_category_edit')->name('admin.blog_category_edit');
            Route::put('/blog/category/update/{id}', 'update_blog_category')->name('admin.update_blog_category');
            Route::delete('/destroy/blog/category/{id}', 'delete_blog_category')->name('admin.blog_category_destroy');
        });

        // Blog Posts
        Route::controller(BlogController::class)->group(function () {
            Route::get('/all/posts', 'all_posts')->name('admin.all_posts')->middleware('permission:blog.menu');
            Route::get('/add/posts', 'add_posts')->name('admin.add_post');
            Route::post('/post/store/{id}', 'store_post')->name('admin.post_store');
            Route::get('/post/edit/{id}', 'post_edit')->name('admin.edit_post');
            Route::put('/post/update/{id}', 'update_post')->name('admin.update_post');
            Route::delete('/destroy/post/{id}', 'delete_post')->name('admin.post_destroy');
        });

        // Roles & Permissions
        Route::controller(RoleController::class)
            ->middleware('permission:rolepermission.menu')
            ->group(function () {
                // Permissions
                Route::get('/all/permissions', 'all_permissions')->name('admin.all_permission');
                Route::get('/add/permission', 'add_permission')->name('admin.add_Permission');
                Route::post('/permission/store', 'store_permission')->name('admin.permission_store');
                Route::get('/permission/edit/{id}', 'permission_edit')->name('admin.edit_permission');
                Route::put('/permission/update/{id}', 'update_permission')->name('admin.update_permission');
                Route::delete('/destroy/permission/{id}', 'permission_delete')->name('admin.permission_destroy');

                // Export/Import
                Route::get('/export/permission', 'export_permission')->name('admin.export_permission');
                Route::get('/import/permission', 'import_permission')->name('admin.import_Permission');
                Route::post('/import/permission', 'import_permission_file')->name('admin.import_file');

                // Roles
                Route::get('/all/roles', 'all_roles')->name('admin.all_role');
                Route::get('/add/role', 'add_role')->name('admin.add_role');
                Route::post('/role/store', 'store_role')->name('admin.role_store');
                Route::get('/role/edit/{id}', 'edit_role')->name('admin.edit_role');
                Route::put('/role/update/{id}', 'update_role')->name('admin.update_role');
                Route::delete('/destroy/role/{id}', 'delete_role')->name('admin.role_destroy');

                // Role Permissions
                Route::get('all/role/permissions', 'all_role_permissions')->name('admin.all_role_permissions');
                Route::get('/add/role/permissions', 'add_role_permissions')->name('admin.add_role_permissions');
                Route::post('/role/permissions/store', 'store_role_permissions')->name('admin.role_permission_store');
                Route::get('/edit/role/permissions/{id}', 'edit_role_permissions')->name('admin.edit_role_permissions');
                Route::put('/update/role/permissions/{id}', 'update_role_permissions')->name('admin.update_role_permissions');
                Route::delete('/destroy/role/permissions/{id}', 'delete_role_permissions')->name('admin.role_permissions_destroy');
            });
    });

