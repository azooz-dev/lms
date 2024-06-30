<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::user()->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'photo' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'username' => 'User Name',
            'email' => 'Email',
            'photo' => 'Photo',
            'phone' => 'Phone',
            'address' => 'Address',
        ];
    }
}
