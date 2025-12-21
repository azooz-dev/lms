<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getByRole(string $role): Collection;

    public function getAllUsers(): Collection;

    public function countByRole(string $role): int;

    public function countByRoleInDateRange(string $role, DateTimeInterface $start, DateTimeInterface $end): int;

    public function getAllInstructors(): Collection;

    public function getAllAdmins(): Collection;

    public function createWithRole(array $data, string $role): User;
}
