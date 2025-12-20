<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    /**
     * Create a new admin user
     */
    public function createAdmin(array $data, string $roleName): User
    {
        $data['role'] = 'admin';
        $data['password'] = Hash::make($data['password']);

        $admin = User::create($data);
        $admin->assignRole($roleName);

        return $admin;
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
        $photoName = null;

        if ($photo) {
            $photoName = $this->fileUploadService->uploadFile($photo, 'upload/instructor_images');
        }

        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'photo' => $photoName,
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'role' => 'instructor',
            'status' => '0',
            'bio' => $data['bio'] ?? null,
        ]);
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
        if ($photo) {
            // Delete old photo if exists
            $this->deleteUserPhoto($user);

            // Upload new photo
            $data['photo'] = $this->fileUploadService->generateTimestampPrefixedFilename($photo);
            $photo->storeAs('public/upload/users_images', $data['photo']);
        } else {
            unset($data['photo']);
        }

        $user->update($data);

        return $user;
    }

    /**
     * Update admin profile with optional photo
     */
    public function updateAdminProfile(User $admin, array $data, ?UploadedFile $photo = null): User
    {
        if ($photo) {
            // Delete old photo if exists
            if (! empty($admin->photo)) {
                $this->fileUploadService->deleteFromPublicStorage('upload/admin_images', $admin->photo);
            }

            // Upload new photo
            $data['photo'] = $this->fileUploadService->generateTimestampPrefixedFilename($photo);
            $photo->storeAs('public/upload/admin_images', $data['photo']);
        } else {
            unset($data['photo']);
        }

        $admin->update($data);

        return $admin;
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
        return User::where('role', 'instructor')
            ->latest()
            ->get();
    }

    /**
     * Get all admin users
     */
    public function getAllAdmins(): Collection
    {
        return User::where('role', 'admin')->get();
    }

    /**
     * Get a user by ID
     */
    public function getUserById(int $id): ?User
    {
        return User::find($id);
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

