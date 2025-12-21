<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordAction
{
    /**
     * Change user password after validating old password
     *
     * @return array{success: bool, message: string}
     */
    public function handle(User $user, string $oldPassword, string $newPassword): array
    {
        // Verify old password
        if (! Hash::check($oldPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'Old password does not match.',
            ];
        }

        // Update password
        $user->update(['password' => Hash::make($newPassword)]);

        return [
            'success' => true,
            'message' => 'Password changed successfully.',
        ];
    }
}

