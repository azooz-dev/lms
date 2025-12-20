<?php

declare(strict_types=1);

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|min:10|max:1000',
            'rate' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please write your review.',
            'message.min' => 'Your review must be at least 10 characters.',
            'message.max' => 'Your review must not exceed 1000 characters.',
            'rate.required' => 'Please select a rating.',
            'rate.integer' => 'The rating must be a valid number.',
            'rate.min' => 'The rating must be at least 1.',
            'rate.max' => 'The rating cannot exceed 5.',
        ];
    }

    public function attributes(): array
    {
        return [
            'message' => 'Review',
            'rate' => 'Rating',
        ];
    }
}

