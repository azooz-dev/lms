<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards )
    {
        
        $guards = empty($guards) ? [null] : $guards;
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    if(Auth::check()) {
                        return redirect('/admin/dashboard');
                    }
                    break;
                case 'instructor':
                    if(Auth::check()) {
                        return redirect('/instructor/dashboard');
                    }
                    break;
                case 'user':
                    if(Auth::check()) {
                        return redirect('dashboard');
                    }
                    break;
                default:
                    return $next($request);
            }
        }
    }

        return $next($request);
    }
}
