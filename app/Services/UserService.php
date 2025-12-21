<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\User\ChangePasswordAction;
use App\Actions\User\CreateAdminAction;
use App\Actions\User\RegisterInstructorAction;
use App\Actions\User\UpdateUserProfileAction;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly FileUploadService $fileUploadService,
        private readonly UserRepositoryInterface $userRepository,
        private readonly RegisterInstructorAction $registerInstructorAction,
        private readonly CreateAdminAction $createAdminAction,
        private readonly UpdateUserProfileAction $updateUserProfileAction,
        private readonly ChangePasswordAction $changePasswordAction
    ) {}

    /**
     * Create a new admin user
     */
    public function createAdmin(array $data, string $roleName): User
    {
        return $this->createAdminAction->handle($data, $roleName);
    }

    /**
     * Update an admin user
     */
    public function updateAdmin(User $admin, array $data, string $roleName): User
    {
        $data['role'] = 'admin';

        $admin->update($data);
        $admin->syncRoles($roleName);

        return $admin;
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user): void
    {
        // Delete profile photo if exists
        if (! empty($user->photo)) {
            $this->deleteUserPhoto($user);
        }

        $user->delete();
    }

    /**
     * Register a new instructor
     */
    public function registerInstructor(array $data, ?UploadedFile $photo = null): User
    {
        return $this->registerInstructorAction->handle($data, $photo);
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleUserStatus(User $user): User
    {
        $user->status = $user->status === '1' ? '0' : '1';
        $user->save();

        return $user;
    }

    /**
     * Update user profile with optional photo
     */
    public function updateProfile(User $user, array $data, ?UploadedFile $photo = null): User
    {
        return $this->updateUserProfileAction->handle($user, $data, $photo);
    }

    /**
     * Update admin profile with optional photo
     */
    public function updateAdminProfile(User $admin, array $data, ?UploadedFile $photo = null): User
    {
        return $this->updateUserProfileAction->handleAdmin($admin, $data, $photo);
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $user->update(['password' => Hash::make($newPassword)]);
    }

    /**
     * Verify old password matches
     */
    public function verifyOldPassword(User $user, string $oldPassword): bool
    {
        return Hash::check($oldPassword, $user->password);
    }

    /**
     * Change user email
     */
    public function changeEmail(User $user, string $newEmail): void
    {
        $user->update(['email' => $newEmail]);
    }

    /**
     * Get all instructors
     */
    public function getAllInstructors(): Collection
    {
        return $this->userRepository->getAllInstructors();
    }

    /**
     * Get all admin users
     */
    public function getAllAdmins(): Collection
    {
        return $this->userRepository->getAllAdmins();
    }

    /**
     * Get a user by ID
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Delete user photo from storage
     */
    private function deleteUserPhoto(User $user): void
    {
        if (empty($user->photo)) {
            return;
        }

        // Try different possible locations
        $this->fileUploadService->deleteFromPublicStorage('upload/users_images', $user->photo);
        $this->fileUploadService->deleteFromPublicStorage('upload/instructor_images', $user->photo);
        $this->fileUploadService->deleteFromPublicStorage('upload/admin_images', $user->photo);
    }
}
