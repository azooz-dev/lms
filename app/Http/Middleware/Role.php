<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Support\Cash;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {


        // If the user is logged in, update their last seen time in the DB
        // and add them to the online list for 30 seconds
        if (Auth::check()) {
            $expireTime = Carbon::now()->addSecond(30);
            // Store the user's ID in the cache for 30 seconds
            Cache::put('user-id-online' . Auth::user()->id, true, $expireTime);
            // Update the user's last seen time in the DB
            User::where('id', Auth::user()->id)
                ->update(['last_seen' => Carbon::now()]);
        }

        // Check the user's role and compare it to the required role
        switch ($request->user()->role) {
            case 'user':
                // If the user is not allowed to access the page, redirect them to the appropriate dashboard
                if ($role !== 'user') {
                    return redirect('dashboard');
                }
                break;
            case 'admin':
                // If the user is an admin and is trying to access a user-only page, redirect them to the admin dashboard
                if ($role === 'user') {
                    return redirect('admin/dashboard');
                }
                break;
            case 'instructor':
                // If the user is an instructor and is trying to access a page only accessible to admins or users, redirect them to the appropriate dashboard
                if ($role === 'user' || $role === 'admin') {
                    return redirect('instructor/dashboard');
                }
                break;
        }
        return $next($request);
    }
}
