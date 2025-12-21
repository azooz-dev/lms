<?php

use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
|
| Routes for the user dashboard, profile, and course management.
| All routes require authentication with user role.
|
*/

Route::middleware(['auth', 'roles:user'])->group(function () {

    // Dashboard & Profile
    Route::controller(UserController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->middleware('verified')->name('dashboard');
        Route::get('/profile/{id}', 'profile')->name('user.profile');
        Route::get('/settings/{id}', 'user_settings')->name('user.settings');
        Route::put('/settings/{update}', 'update_profile')->name('user.update_profile');
        Route::put('/settings/password/{update}', 'change_password')->name('user.change_password');
        Route::put('/settings/email/{update}', 'change_email')->name('user.change_email');
        Route::get('/logout', 'logout')->name('user.logout');
    });

    // My Courses
    Route::controller(OrderController::class)->group(function () {
        Route::get('/my/courses/{id}', 'my_courses')->name('user.my_course');
        Route::get('/my/course/details/{id}', 'my_course_details')->name('user.course_details');
        Route::delete('/delete/my/course/{id}', 'delete_my_course')->name('user.delete_course');
    });

    // Questions
    Route::controller(QuestionController::class)->group(function () {
        Route::post('/send/question/{course}/{instructor}/{user}', 'send_question')->name('user.send_question');
        Route::get('/question/replies/{id}', 'question_replies')->name('question_replies');
    });
});
