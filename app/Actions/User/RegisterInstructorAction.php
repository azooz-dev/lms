<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Events\InstructorRegistered;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class RegisterInstructorAction
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    /**
     * Register a new instructor
     */
    public function handle(array $data, ?UploadedFile $photo = null): User
    {
        $photoName = null;

        if ($photo) {
            $photoName = $this->fileUploadService->uploadFile($photo, 'upload/instructor_images');
        }

        $instructor = User::create([
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

        // Dispatch event to send welcome email
        InstructorRegistered::dispatch($instructor);

        return $instructor;
    }
}
