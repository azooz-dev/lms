<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use DateTimeInterface;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new User);
    }

    public function getByRole(string $role): Collection
    {
        return User::where('role', $role)->get();
    }

    public function getAllUsers(): Collection
    {
        return User::where('role', 'user')->get();
    }

    public function countByRole(string $role): int
    {
        return User::where('role', $role)->count();
    }

    public function countByRoleInDateRange(string $role, DateTimeInterface $start, DateTimeInterface $end): int
    {
        return User::where('role', $role)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }

    public function getAllInstructors(): Collection
    {
        return User::where('role', 'instructor')
            ->latest()
            ->get();
    }

    public function getAllAdmins(): Collection
    {
        return User::where('role', 'admin')->get();
    }
}
