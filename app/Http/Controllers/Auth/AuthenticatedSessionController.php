<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('frontend.authentication.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->role == UserRole::ADMIN) { // For Admin Authenticated
            return redirect()->intended('/admin/dashboard');
        } elseif (Auth::user()->role == UserRole::INSTRUCTOR) { // For Instructor Authenticated
            return redirect()->intended('/instructor/dashboard');
        } elseif (Auth::user()->role == UserRole::USER) { // For User Authenticated
            return redirect()->intended('/');
        }

        // Default fallback for any other role
        return redirect()->intended('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
