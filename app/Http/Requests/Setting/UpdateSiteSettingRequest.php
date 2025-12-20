<?php

declare(strict_types=1);

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email_site' => 'required|email|max:255',
            'phone_site' => 'required|string|max:50',
            'address_site' => 'required|string|max:500',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'copyright' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a JPEG, PNG, JPG, GIF, or SVG file.',
            'logo.max' => 'The logo size must not exceed 2MB.',
            'email_site.required' => 'The site email is required.',
            'email_site.email' => 'Please enter a valid email address.',
            'phone_site.required' => 'The site phone number is required.',
            'address_site.required' => 'The site address is required.',
            'facebook.url' => 'Please enter a valid Facebook URL.',
            'twitter.url' => 'Please enter a valid Twitter URL.',
            'instagram.url' => 'Please enter a valid Instagram URL.',
            'linkedin.url' => 'Please enter a valid LinkedIn URL.',
            'copyright.required' => 'The copyright text is required.',
        ];
    }

    public function attributes(): array
    {
        return [
            'logo' => 'Site Logo',
            'email_site' => 'Site Email',
            'phone_site' => 'Site Phone',
            'address_site' => 'Site Address',
            'facebook' => 'Facebook URL',
            'twitter' => 'Twitter URL',
            'instagram' => 'Instagram URL',
            'linkedin' => 'LinkedIn URL',
            'copyright' => 'Copyright Text',
        ];
    }
}

