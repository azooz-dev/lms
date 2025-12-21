<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class CreateAdminAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Create a new admin user with role assignment
     */
    public function handle(array $data, string $roleName): User
    {
        $admin = $this->userRepository->createWithRole($data, 'admin');
        $admin->assignRole($roleName);

        return $admin;
    }
}
