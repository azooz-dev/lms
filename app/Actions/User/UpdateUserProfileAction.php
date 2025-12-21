<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;

class UpdateUserProfileAction
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    /**
     * Update user profile with optional photo
     */
    public function handle(User $user, array $data, ?UploadedFile $photo = null): User
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
    public function handleAdmin(User $admin, array $data, ?UploadedFile $photo = null): User
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

