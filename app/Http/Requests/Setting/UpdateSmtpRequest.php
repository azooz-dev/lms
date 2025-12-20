<?php

declare(strict_types=1);

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mailer' => 'required|string|max:50',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'encryption' => 'required|string|in:tls,ssl,starttls',
            'from_address' => 'required|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'mailer.required' => 'The mailer type is required.',
            'host.required' => 'The SMTP host is required.',
            'port.required' => 'The port number is required.',
            'port.integer' => 'The port must be a valid number.',
            'port.min' => 'The port must be at least 1.',
            'port.max' => 'The port cannot exceed 65535.',
            'username.required' => 'The SMTP username is required.',
            'password.required' => 'The SMTP password is required.',
            'encryption.required' => 'The encryption type is required.',
            'encryption.in' => 'The encryption must be TLS, SSL, or STARTTLS.',
            'from_address.required' => 'The from address is required.',
            'from_address.email' => 'Please enter a valid email address.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mailer' => 'Mailer',
            'host' => 'SMTP Host',
            'port' => 'Port',
            'username' => 'Username',
            'password' => 'Password',
            'encryption' => 'Encryption',
            'from_address' => 'From Address',
        ];
    }
}

