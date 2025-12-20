<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$userId,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Your name is required.',
            'username.required' => 'A username is required.',
            'username.unique' => 'This username is already taken.',
            'phone.required' => 'Your phone number is required.',
            'address.required' => 'Your address is required.',
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'The image must be a JPEG, PNG, or JPG file.',
            'photo.max' => 'The image size must not exceed 2MB.',
            'bio.max' => 'The bio must not exceed 1000 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'username' => 'Username',
            'photo' => 'Photo',
            'phone' => 'Phone',
            'address' => 'Address',
            'bio' => 'Bio',
        ];
    }
}
