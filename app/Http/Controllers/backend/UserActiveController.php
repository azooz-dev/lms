<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\View\View;

class UserActiveController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function all_users(): View
    {
        $users = $this->userService->getAllUsers();

        return view('admin.backend.users.all_users', compact('users'));
    }

    public function all_Instructors(): View
    {
        $instructors = $this->userService->getAllInstructors();

        return view('admin.backend.users.all_instructors', compact('instructors'));
    }
}
