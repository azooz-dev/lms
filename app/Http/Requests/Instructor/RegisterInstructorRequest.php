<?php

declare(strict_types=1);

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterInstructorRequest extends FormRequest
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
            'bio' => 'nullable|string|max:1000',
            'password' => ['required', 'confirmed', Password::defaults(), 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Your name is required.',
            'username.required' => 'A username is required.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Your email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Your phone number is required.',
            'address.required' => 'Your address is required.',
            'password.required' => 'A password is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
            'photo.image' => 'The file must be an image.',
            'photo.max' => 'The image size must not exceed 2MB.',
            'bio.max' => 'The bio must not exceed 1000 characters.',
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
            'bio' => 'Bio',
            'password' => 'Password',
        ];
    }
}

