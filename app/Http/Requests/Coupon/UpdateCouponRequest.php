<?php

declare(strict_types=1);

namespace App\Http\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $couponId = $this->route('id');

        return [
            'coupon_name' => 'required|string|max:50|unique:coupons,coupon_name,'.$couponId,
            'coupon_discount' => 'required|numeric|min:1|max:100',
            'coupon_validity' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_name.required' => 'The coupon code is required.',
            'coupon_name.unique' => 'This coupon code already exists.',
            'coupon_name.max' => 'The coupon code must not exceed 50 characters.',
            'coupon_discount.required' => 'The discount percentage is required.',
            'coupon_discount.numeric' => 'The discount must be a number.',
            'coupon_discount.min' => 'The discount must be at least 1%.',
            'coupon_discount.max' => 'The discount cannot exceed 100%.',
            'coupon_validity.required' => 'The validity date is required.',
            'coupon_validity.date' => 'Please enter a valid date.',
        ];
    }

    public function attributes(): array
    {
        return [
            'coupon_name' => 'Coupon Code',
            'coupon_discount' => 'Discount',
            'coupon_validity' => 'Validity Date',
        ];
    }
}

