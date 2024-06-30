<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserActiveController extends Controller
{
    public function all_users() {
        $users = User::where('role', 'user')->get();

        return view('admin.backend.users.all_users', compact('users'));
    }

    public function all_Instructors() {
        $instructors = User::where('role', 'instructor')->get();

        return view('admin.backend.users.all_instructors', compact('instructors'));
    }
}
