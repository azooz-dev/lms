<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$adminId,
            'email' => 'required|string|email|max:255|unique:users,email,'.$adminId,
            'phone' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'required|string|max:255',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The admin name is required.',
            'username.required' => 'The username is required.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'The email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'The phone number is required.',
            'address.required' => 'The address is required.',
            'role.required' => 'Please select a role.',
            'role.exists' => 'The selected role does not exist.',
            'photo.image' => 'The file must be an image.',
            'photo.max' => 'The image size must not exceed 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'Phone',
            'photo' => 'Photo',
            'address' => 'Address',
            'role' => 'Role',
        ];
    }
}

