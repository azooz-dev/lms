<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::defaults(), 'min:8'],
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
            'password.required' => 'The password is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
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
            'password' => 'Password',
            'role' => 'Role',
        ];
    }
}

