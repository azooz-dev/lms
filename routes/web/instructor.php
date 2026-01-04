<?php

use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\CourseController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\QuestionController;
use App\Http\Controllers\backend\ReviewController;
use App\Http\Controllers\InstructorController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Instructor Routes
|--------------------------------------------------------------------------
|
| Routes for the instructor dashboard and course management.
| All routes are prefixed with /instructor and require instructor role.
|
*/

// Instructor Login (public)
Route::get('/instructor/login', [InstructorController::class, 'login'])
    ->name('instructor.login')
    ->middleware(RedirectIfAuthenticated::class);

// Instructor Authenticated Routes
Route::middleware(['auth', 'roles:instructor'])
    ->prefix('instructor')
    ->group(function () {

        // Dashboard & Profile
        Route::controller(InstructorController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('instructor.dashboard');
            Route::get('/dashboard/chart-data', 'getChartData')->name('instructor.chart_data');
            Route::get('/logout', 'logout')->name('instructor.logout');
            Route::get('/profile', 'instructor_profile')->name('instructor.profile');
            Route::put('/update/{update}', 'instructor_update')->name('instructor.update');
            Route::get('/change_password', 'change_password')->name('instructor.change_password');
            Route::put('/update/password/{update}', 'update_password')->name('instructor.update_password');
        });

        // Courses
        Route::controller(CourseController::class)->group(function () {
            Route::get('/all/courses/{id}', 'all_courses_by_instructor')->name('instructor.all_courses');
            Route::get('/add/course', 'add_course')->name('instructor.add_course');
            Route::get('/get/subCategories/{id}', 'get_subCategories')->name('instructor.get_subCategories');
            Route::post('/course/store', 'store_course')->name('instructor.course_store');
            Route::get('/course/{id}/edit', 'edit_course')->name('instructor.edit_course');
            Route::put('/course/update/{update}', 'update_course')->name('instructor.update_course');
            Route::put('/course/video/{update}', 'update_video')->name('instructor.update_video');
            Route::put('/course/goals/{update}', 'update_goals')->name('instructor.update_goals');
            Route::delete('/course/delete/{delete}', 'destroy_course')->name('instructor.course_destroy');
        });

        // Sections & Lectures
        Route::controller(CourseController::class)->group(function () {
            Route::get('/section/create/{id}', 'create_section')->name('instructor.create_section');
            Route::post('/section/store/{id}', 'store_section')->name('instructor.section_store');
            Route::delete('/section/delete/{delete}', 'destroy_section')->name('instructor.section_destroy');

            Route::post('/lecture/store/{id}', 'store_lecture')->name('instructor.lecture_store');
            Route::get('/lecture/{id}/edit', 'edit_lecture')->name('instructor.edit_lecture');
            Route::put('/lecture/update/{update}', 'update_lecture')->name('instructor.update_lecture');
            Route::delete('/lecture/delete/{delete}', 'destroy_lecture')->name('instructor.lecture_destroy');
        });

        // Orders
        Route::controller(OrderController::class)->group(function () {
            Route::get('/all/order/{id}', 'all_instructor_order')->name('instructor.all_orders');
            Route::get('/order/details/{id}', 'instructor_order_details')->name('instructor.order_details');
            Route::get('/invoice/download/{id}', 'instructor_invoice_download')->name('instructor.invoice_download');
            Route::get('/mark/notification/read/{id}', 'mark_notification_read')->name('mark-notification-read');
        });

        // Questions
        Route::controller(QuestionController::class)->group(function () {
            Route::get('/all/question/{id}', 'all_instructor_question')->name('instructor.all_questions');
            Route::get('/details/question/{course}/{user}', 'details_instructor_question')->name('instructor.details_questions');
            Route::post('/reply/question/{user}/{course}', 'reply_instructor_question')->name('instructor.reply_question');
        });

        // Coupons
        Route::controller(CouponController::class)->group(function () {
            Route::get('/all/coupons/{id}', 'all_instructor_coupons')->name('instructor.all_coupons');
            Route::get('/add/coupon/{id}', 'add_instructor_coupon')->name('instructor.add_coupon');
            Route::post('/coupon/store/{id}', 'store_instructor_coupon')->name('instructor.coupon_store');
            Route::get('/edit/coupon/{id}', 'edit_instructor_coupon')->name('instructor.edit_coupon');
            Route::put('/update/coupon/{id}', 'update_instructor_coupon')->name('instructor.update_coupon');
            Route::delete('/destroy/coupon/{id}', 'delete_instructor_coupon')->name('instructor.destroy_coupon');
        });

        // Reviews
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/all/reviews/{id}', 'instructor_reviews')->name('instructor.reviews');
        });
    });
