<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'old_email' => 'required|email|exists:users,email',
            'new_email' => 'required|email|unique:users,email',
            'email_confirmation' => 'required|same:new_email',
        ];
    }


    public function messages(): array
    {
        return [
            'old_email.required' => 'The old email field is required.',
            'old_email.email' => 'The old email must be a valid email address.',
            'old_email.exists' => 'The old email does not match your current email.',
            'new_email.required' => 'The new email field is required.',
            'new_email.email' => 'The new email must be a valid email address.',
            'new_email.unique' => 'The new email has already been taken.',
            'email_confirmation.required' => 'The email confirmation field is required.',
            'email_confirmation.same' => 'The email confirmation does not match the new email.',
        ];
    }

    public function attributes() :array
    {
        return [
            'old_email' => 'Old email',
            'new _email' => 'New email',
            'email_confirmation' => 'Email confirmation',
        ];
    }
}
