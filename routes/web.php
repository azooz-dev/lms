<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Web routes are loaded from domain-specific files in routes/web/ directory.
| Each file handles a specific area of the application:
|
| - frontend.php: Public routes (homepage, courses, cart, blog)
| - user.php: User dashboard routes (profile, my courses)
| - admin.php: Admin dashboard routes (management features)
| - instructor.php: Instructor routes (course management)
|
*/

// Frontend Routes (public)
require __DIR__.'/web/frontend.php';

// User Dashboard Routes (authenticated users)
require __DIR__.'/web/user.php';

// Admin Dashboard Routes (admin role)
require __DIR__.'/web/admin.php';

// Instructor Routes (instructor role)
require __DIR__.'/web/instructor.php';

// Authentication Routes (Laravel Breeze/Fortify)
require __DIR__.'/auth.php';
