<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\backend\BlogController;
use App\Http\Controllers\backend\ReviewController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\IndexController;
use App\Http\Controllers\frontend\WishListController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Public routes accessible without authentication.
| Includes homepage, courses, cart, wishlist, blog, and checkout.
|
*/

// Homepage
Route::get('/', [UserController::class, 'index'])->name('index');

// Become Instructor (public registration)
Route::get('/become/instructor', [AdminController::class, 'become_instructor'])->name('become_instructor');
Route::post('/instructor/register', [AdminController::class, 'instructor_register'])->name('register_instructor');

// Course Browsing
Route::controller(IndexController::class)->group(function () {
    Route::get('/course/details/{id}/{slug}', 'course_details')->name('course_details');
    Route::get('/category/courses/{id}/{slug}', 'category_courses')->name('category_courses');
    Route::get('/subCategory/courses/{id}/{slug}', 'subCategory_courses')->name('subCategory_courses');
    Route::get('/instructor/details/{id}', 'instructor_details')->name('instructor_details');
});

// Wishlist
Route::controller(WishListController::class)->group(function () {
    Route::post('/wishlist/store', 'store_wishList')->name('wishlist.store');
    Route::get('/wishlist/all', 'wishList_view')->name('user.wishlist');
    Route::get('/all/wishlist/{id}', 'all_wishList')->name('wishlist.all');
    Route::delete('/remove/wishlist/{id}/{course}', 'delete_wishlist')->name('destroy_wishlist');
});

// Cart
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

    // Coupon
    Route::post('/apply/coupon', 'apply_coupon')->name('apply_coupon');
    Route::get('/cart/calculation', 'cart_calculation')->name('cart_calculation');
    Route::delete('/remove/coupon', 'remove_coupon')->name('remove_coupon');

    // Checkout
    Route::get('/checkout', 'checkout')->name('checkout');

    // Payment
    Route::post('/payment/process', 'payment_process')->name('payment.process');
});

// Reviews
Route::controller(ReviewController::class)->group(function () {
    Route::post('/store/review/{id}/{course}', 'review_store')->name('review_store');
});

// Blog
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog/details/{slug}', 'blog_details')->name('blog_details');
    Route::get('/blog/category/details/{id}', 'blog_category_details')->name('blog_category_details');
    Route::get('/all/blog', 'all_blog')->name('blogs');
});
