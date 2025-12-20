<?php

declare(strict_types=1);

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupon_name' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_name.required' => 'Please enter a coupon code.',
            'coupon_name.max' => 'The coupon code is too long.',
        ];
    }

    public function attributes(): array
    {
        return [
            'coupon_name' => 'Coupon Code',
        ];
    }
}
