<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {

        if ($request->is('admin/*')) {
            return $request->expectsJson() ? null : route('admin.login');
        }

        if ($request->is('instructor/*')) {
            return $request->expectsJson() ? null : route('instructor.login');
        }
        
        return $request->expectsJson() ? null : route('login');
    }
}