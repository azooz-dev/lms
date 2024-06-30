<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\backend\BlogController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\CourseController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\QuestionController;
use App\Http\Controllers\backend\ReportController;
use App\Http\Controllers\backend\ReviewController;
use App\Http\Controllers\backend\RoleController;
use App\Http\Controllers\backend\SettingController;
use App\Http\Controllers\backend\SubCategoryController;
use App\Http\Controllers\backend\UserActiveController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\IndexController;
use App\Http\Controllers\frontend\WishListController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User Group Middleware
Route::controller(UserController::class)->group(function () {

    // All User Routes
    Route::middleware(['auth', 'roles:user'])->group(function () {
        Route::get('/dashboard', 'dashboard')->middleware('verified')->name('dashboard');
        Route::get('/profile/{id}', 'profile')->name('user.profile');
        Route::get('/settings/{id}', 'user_settings')->name('user.settings');
        Route::put('/settings/{update}', 'update_profile')->name('user.update_profile');
        Route::put('/settings/password/{update}', 'change_password')->name('user.change_password');
        Route::put('/settings/email/{update}', 'change_email')->name('user.change_email');
        Route::get('/logout', 'logout')->name('user.logout');



        // User Course Routes
        Route::controller(OrderController::class)->group(function () {
            Route::get('/my/courses/{id}', 'my_courses')->name('user.my_course');
            Route::get('/my/course/details/{id}', 'my_course_details')->name('user.course_details');
        });


        // User Question Routes
        Route::controller(QuestionController::class)->group(function () {
            Route::post('/send/question/{course}/{instructor}/{user}', 'send_question')->name('user.send_question');
            Route::get('/question/replies/{id}', 'question_replies')->name('question_replies');
        });
    });
});

Route::get('/', 'UserController@index')->name('index');


// Admin Group Middleware
Route::controller(AdminController::class)->group(function () {

    // All Admin Routes
    Route::middleware(['auth', 'roles:admin'])->prefix('admin')->group(function () {
        // Manage Profile Routes
        Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
        Route::get('/logout', 'logout')->name('admin.logout');
        Route::get('/profile', 'admin_profile')->name('admin.profile');
        Route::put('/update/{update}', 'admin_update')->name('admin.update');
        Route::get('/change_password', 'change_password')->name('admin.change_password');
        Route::put('/update/password/{update}', 'update_password')->name('admin.update_password');
        Route::get('/all/admins', 'all_admins')->name('admin.all_admins');
        Route::get('/add/admins', 'add_admins')->name('admin.add_admin');
        Route::post('/admin/store', 'store_admin')->name('admin.admin_store');
        Route::get('/admin/edit/{id}', 'edit_admin')->name('admin.edit_admin');
        Route::put('/admin/update/{id}', 'update_admin')->name('admin.update_admin');
        Route::delete('/admin/delete/{id}', 'delete_admin')->name('admin.admin_destory');

        // Theme Routes
        Route::post('/update-theme', 'update_theme')->name('update-theme');
        Route::get('/get-theme-preference', 'ThemeController@getThemePreference')->name('get-theme-preference');


        // All Categories Routes
        Route::controller(CategoryController::class)->group(function () {

            Route::get('/all/categories', 'all_categories')->name('admin.all_categories')->middleware('permission:category.all');
            Route::get('/category/add', 'add_category')->name('admin.add_category')->middleware('permission:category.add');
            Route::post('/category/store', 'store_category')->name('admin.category_store');
            Route::get('/category/{id}/edit', 'edit_category')->name('admin.edit_category')->middleware('permission:category.edit');
            Route::put('/category/update/{update}', 'update_category')->name('admin.update_category');
            Route::delete('/category/delete/{delete}', 'destory_category')->name('admin.category_destory')->middleware('permission:category.delete');
        });

        // All SubCategories Routes 
        Route::controller(SubCategoryController::class)->group(function () {

            Route::get('/all/subCategories', 'all_subCategories')->name('admin.all_subCategories')->middleware('permission:subcategory.all');
            Route::get('/subCategory/add', 'add_subCategory')->name('admin.add_subCategory');
            Route::post('/subCategory/store', 'store_subCategory')->name('admin.subCategory_store');
            Route::get('/subCategory/{id}/edit', 'edit_subCategory')->name('admin.edit_subCategory');
            Route::put('/subCategory/update/{update}', 'update_subCategory')->name('admin.update_subCategory');
            Route::delete('/subCategory/delete/{delete}', 'destory_subCategory')->name('admin.subCategory_destory');
        });



        // All Coupon Routes
        Route::controller(CouponController::class)->group(function () {
            Route::get('/all/coupons', 'all_coupons')->name('admin.all_coupons')->middleware('permission:coupon.all');
            Route::get('/coupon/add', 'add_coupon')->name('admin.add_coupon')->middleware('permission:coupon.add');
            Route::post('/coupon/store', 'store_coupon')->name('admin.coupon_store');
            Route::get('/coupon/{id}/edit', 'edit_coupon')->name('admin.edit_coupon')->middleware('permission:coupon.edit');
            Route::put('/coupon/update/{id}', 'update_coupon')->name('admin.update_coupon');
            Route::delete('/coupon/delete/{id}', 'destory_coupon')->name('admin.coupon_destory')->middleware('permission:coupon.delete');
        });


        // All Instructors Routes
        Route::get('/all/instructors', 'all_instructors')->name('admin.all_instructors')->middleware('permission:instructor.menu');
        Route::put('/instructor/{id}/status', 'update_instructor_status')->name('admin.update_instructor_status');

        // All Courses Routes
        Route::get('/all/courses', 'all_courses')->name('admin.all_courses');
        Route::put('/course/{id}/status', 'update_course_status')->name('admin.update_course_status');
        Route::get('/course/details/{id}', 'course_details')->name('admin.course_details');


        // All Settings Routes
        Route::controller(SettingController::class)->group(function () {
            Route::get('/smtp/setting', 'smtp_setting')->name('admin.smtp_setting')->middleware('permission:setting.menu');
            Route::put('/smtp/update/{id}', 'smtp_update')->name('admin.update_smtp');
            Route::get('/site/setting', 'site_setting')->name('admin.site_setting');
            Route::put('/site/setting/update/{id}', 'site_setting_update')->name('admin.update_site_setting');
        });

        // All Orders Routes
        Route::controller(OrderController::class)->group(function () {
            Route::get('/pending/order', 'pending_order')->name('admin.pending_order')->middleware('permission:order.menu');
            Route::get('/order/details/{id}', 'order_details')->name('admin.order_details');
            Route::put('/order/update/status/{id}', 'update_order_status')->name('admin.update_order_status');
            Route::get('/confirm/order', 'confirm_order')->name('admin.confirm_order')->middleware('permission:order.menu');
        });


        // All Reports Routes
        Route::controller(ReportController::class)->group(function () {
            Route::get('/all/reports', 'all_reports')->name('admin.all_reports')->middleware('permission:report.menu');
            Route::post('/date/reports', 'date_reports')->name('admin.date_reports');
        });


        // All Reviews Routes
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/pending/reviews', 'pending_reviews')->name('admin.pending_reviews')->middleware('permission:review.menu');
            Route::get('/active/reviews', 'active_reviews')->name('admin.active_reviews');
            Route::put('update/review/status/{id}', 'update_review_status')->name('admin.update_review_status');
        });


        // All Users Active Routes
        Route::controller(UserActiveController::class)->group(function () {
            Route::get('all/users', 'all_users')->name('admin.all_users')->middleware('permission:all.user.menu');
            Route::get('all/Instructors', 'all_Instructors')->name('admin.Instructors')->middleware('permission:all.user.menu');
        });


        // All Blog Category Routes
        Route::controller(BlogController::class)->group(function () {
            Route::get('all/blog/category', 'all_blog_category')->name('admin.all_blog_category')->middleware('permission:blog.menu');
            Route::post('/store/blog/category', 'store_blog_category')->name('admin.store_blog_category');
            Route::get('/blog/category/edit/{id}', 'blog_category_edit')->name('admin.blog_category_edit');
            Route::put('/blog/category/update/{id}', 'update_blog_category')->name('admin.update_blog_category');
            Route::delete('/destroy/blog/category/{id}', 'delete_blog_category')->name('admin.blog_category_destory');
        });


        // All Blog Posts Routes
        Route::controller(BlogController::class)->group(function () {
            Route::get('/all/posts', 'all_posts')->name('admin.all_posts')->middleware('permission:blog.menu');
            Route::get('/add/posts', 'add_posts')->name('admin.add_post');
            Route::post('/post/store/{id}', 'store_post')->name('admin.post_store');
            Route::get('/post/edit/{id}', 'post_edit')->name('admin.edit_post');
            Route::put('/post/update/{id}', 'update_post')->name('admin.update_post');
            Route::delete('/destory/post/{id}', 'delete_post')->name('admin.post_destory');
        });

        // All Role & Permissions Routes
        Route::controller(RoleController::class)->group(function () {
            // All Permissions Routes
            Route::get('/all/permissions', 'all_permissions')->name('admin.all_permission');
            Route::get('/add/permission', 'add_permission')->name('admin.add_Permission');
            Route::post('/permission/store', 'store_permission')->name('admin.permission_store');
            Route::get('/permission/edit/{id}', 'permission_edit')->name('admin.edit_permission');
            Route::put('/permission/update/{id}', 'update_permission')->name('admin.update_permission');
            Route::delete('/destory/permission/{id}', 'permission_delete')->name('admin.permission_destory');

            // All Export Import Files 
            Route::get('/export/permission', 'export_permission')->name('admin.export_permission');
            Route::get('/import/permission', 'import_permission')->name('admin.import_Permission');
            Route::post('/import/permission', 'import_permission_file')->name('admin.import_file');

            // All Roles Routes
            Route::get('/all/roles', 'all_roles')->name('admin.all_role');
            Route::get('/add/role', 'add_role')->name('admin.add_role');
            Route::post('/role/store', 'store_role')->name('admin.role_store');
            Route::get('/role/edit/{id}', 'edit_role')->name('admin.edit_role');
            Route::put('/role/update/{id}', 'update_role')->name('admin.update_role');
            Route::delete('/destory/role/{id}', 'delete_role')->name('admin.role_destory');

            // All Role In Permissions
            Route::get('all/role/permissions', 'all_role_permissions')->name('admin.all_role_permissions');
            Route::get('/add/role/permissions', 'add_role_permissions')->name('admin.add_role_permissions');
            Route::post('/role/permissions/store', 'store_role_permissions')->name('admin.role_permission_store');
            Route::get('/edit/role/permissions/{id}', 'edit_role_permissions')->name('admin.edit_role_permissions');
            Route::put('/update/role/permissions/{id}', 'update_role_permissions')->name('admin.update_role_permissions');
            Route::delete('/destory/role/permissions/{id}', 'delete_role_permissions')->name('admin.role_permissions_destory');
        })->middleware('permission:rolepermission.menu');
    });

    // All Instructors Routes
    Route::get('/become/instructor', 'AdminController@become_instructor')->name('become_instructor');
    Route::post('/instructor/register', 'AdminController@instructor_register')->name('register_instructor');


});

Route::get('/admin/login', 'AdminController@login')->name('admin.login')->middleware(RedirectIfAuthenticated::class);



// Instructor Group Middleware
Route::controller(InstructorController::class)->prefix('instructor')->group(function () {

    // All Instructor Routes
    Route::middleware(['auth', 'roles:instructor'])->group(function () {
        Route::get('/dashboard', 'dashboard')->name('instructor.dashboard');
        Route::get('/logout', 'logout')->name('instructor.logout');
        Route::get('/profile', 'instructor_profile')->name('instructor.profile');
        Route::put('/update/{update}', 'instructor_update')->name('instructor.update');
        Route::get('/change_password', 'change_password')->name('instructor.change_password');
        Route::put('/update/password/{update}', 'update_password')->name('instructor.update_password');


        // All Courses Routes
        Route::controller(CourseController::class)->group(function () {
            Route::get('/all/courses/{id}', 'all_courses_by_instructor')->name('instructor.all_courses');
            Route::get('/add/course', 'add_course')->name('instructor.add_course');
            Route::get('/get/subCategories/{id}', 'get_subCategories')->name('instructor.get_subCategories');
            Route::post('/course/store', 'store_course')->name('instructor.course_store');
            Route::get('/course/{id}/edit', 'edit_course')->name('instructor.edit_course');
            Route::put('/course/update/{update}', 'update_course')->name('instructor.update_course');
            Route::put('/course/video/{update}', 'update_video')->name('instructor.update_video');
            Route::put('/course/goals/{update}', 'update_goals')->name('instructor.update_goals');
            Route::delete('/course/delete/{delete}', 'destory_course')->name('instructor.course_destory');
        });

        // All Sections Course Routes And Lectures Course Routes
        Route::controller(CourseController::class)->group(function () {
            Route::get('/section/create/{id}', 'create_section')->name('instructor.create_section');
            Route::post('/section/store/{id}', 'store_section')->name('instructor.section_store');
            Route::delete('/section/delete/{delete}', 'destory_section')->name('instructor.section_destory');

            Route::post('/lecture/store/{id}', 'store_lecture')->name('instructor.lecture_store');
            Route::get('/lecture/{id}/edit', 'edit_lecture')->name('instructor.edit_lecture');
            Route::put('/lecture/update/{update}', 'update_lecture')->name('instructor.update_lecture');
            Route::delete('/lecture/delete/{delete}', 'destory_lecture')->name('instructor.lecture_destory');
        });

        // All Orders Routes
        Route::controller(OrderController::class)->group(function () {
            Route::get('/all/order/{id}', 'all_instructor_order')->name('instructor.all_orders');
            Route::get('/order/details/{id}', 'instructor_order_details')->name('instructor.order_details');
            Route::get('/invoice/download/{id}', 'instructor_invoice_download')->name('instructor.invoice_download');
            Route::get('/mark/notification/read/{id}', 'mark_notification_read')->name('mark-notification-read');
        });


        // All Questions Routes
        Route::controller(QuestionController::class)->group(function () {
            Route::get('/all/question/{id}', 'all_instructor_question')->name('instructor.all_questions');
            Route::get('/details/question/{course}/{user}', 'details_instructor_question')->name('instructor.details_questions');
            Route::post('/reply/question/{user}/{course}', 'reply_instructor_question')->name('instructor.reply_question');
        });

        // All Coupons Routes
        Route::controller(CouponController::class)->group(function () {
            Route::get('/all/coupons/{id}', 'all_instructor_coupons')->name('instructor.all_coupons');
            Route::get('/add/coupon/{id}', 'add_instructor_coupon')->name('instructor.add_coupon');
            Route::post('/coupon/store/{id}', 'store_instructor_coupon')->name('instructor.coupon_store');
            Route::get('/edit/coupon/{id}', 'edit_instructor_coupon')->name('instructor.edit_coupon');
            Route::put('/update/coupon/{id}', 'update_instructor_coupon')->name('instructor.update_coupon');
            Route::delete('/destory/coupon/{id}', 'delete_instructor_coupon')->name('instructor.destory_coupon');
        });


        // All Instructor Reviews Routes
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/all/reviews/{id}', 'instructor_reviews')->name('instructor.reviews');
        });

    });
});


Route::get('/instructor/login', 'InstructorController@login')->name('instructor.login')->middleware(RedirectIfAuthenticated::class);




// All Routes Accessible Without Middleware
Route::controller(IndexController::class)->group(function () {
    Route::get('/course/details/{id}/{slug}', 'course_details')->name('course_details');
    Route::get('/category/courses/{id}/{slug}', 'category_courses')->name('category_courses');
    Route::get('/subCategory/courses/{id}/{slug}', 'subCategory_courses')->name('subCategory_courses');
    Route::get('/instructor/details/{id}', 'instructor_details')->name('instructor_details');
});



Route::controller(WishListController::class)->group(function () {
    Route::post('/wishlist/store', 'store_wishList')->name('wishlist.store');
    Route::get('/wishlist/all', 'wishList_view')->name('user.wishlist');
    Route::get('/all/wishlist/{id}', 'all_wishList')->name('wishlist.all');
    Route::delete('/remove/wishlist/{id}/{course}', 'delete_wishlist')->name('destory_wishlist');
});



Route::controller(CartController::class)->group(function () {
    // Mini Cart
    Route::post('/cart/store/{id}', 'store_cart')->name('cart.store');
    Route::post('/buy/course/{id}', 'buy_course')->name('buy.course');
    Route::get('mini/cart/all', 'mini_cart')->name('mini_cart');
    Route::delete('/mini/cart/delete/{id}', 'mini_cart_delete')->name('mini_cart_delete');

    // My Cart
    Route::get('/show/cart', 'show_cart')->name('show_cart');
    Route::get('/get/cart/content', 'cart_content')->name('my_cart_content');
    Route::delete('/remove/course/cart/{id}', 'remove_course_cart')->name('remove_Course_cart');

    // Apply Coupon
    Route::post('/apply/coupon', 'apply_coupon')->name('apply_coupon');
    Route::get('/cart/calculation', 'cart_calculation')->name('cart_calculation');
    Route::delete('/remove/coupon', 'remove_coupon')->name('remove_coupon');


    // Checkout Routes
    Route::get('/checkout', 'checkout')->name('checkout');

    // Payment Routes
    Route::post('/payment/process', 'payment_process')->name('payment.process');
});


Route::controller(ReviewController::class)->group(function () {
    Route::post('/store/review/{id}/{course}', 'review_store')->name('review_store');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/blog/details/{slug}', 'blog_details')->name('blog_details');
    Route::get('/blog/category/details/{id}', 'blog_category_details')->name('blog_category_details');
    Route::get('/all/blog', 'all_blog')->name('blogs');
});


require __DIR__ . '/auth.php';