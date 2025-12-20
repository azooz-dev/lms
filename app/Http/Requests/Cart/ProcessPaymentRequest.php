<?php

declare(strict_types=1);

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'cash_delivery' => 'required|in:credit_card,cash',
            'course_id' => 'required|array|min:1',
            'course_id.*' => 'required|exists:courses,id',
            'course_title' => 'required|array|min:1',
            'course_title.*' => 'required|string',
            'instructor_id' => 'required|array|min:1',
            'instructor_id.*' => 'required|exists:users,id',
        ];

        // Add credit card validation only if payment method is credit card
        if ($this->input('cash_delivery') === 'credit_card') {
            $rules['card_number'] = 'required|string|digits:16';
            $rules['expiry_month'] = 'required|string|digits:2';
            $rules['expiry_year'] = 'required|string|digits:4';
            $rules['cardCVV'] = 'required|string|digits_between:3,4';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Your name is required.',
            'email.required' => 'Your email is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Your phone number is required.',
            'address.required' => 'Your address is required.',
            'cash_delivery.required' => 'Please select a payment method.',
            'cash_delivery.in' => 'Invalid payment method selected.',
            'course_id.required' => 'No courses selected for purchase.',
            'card_number.required' => 'Card number is required for credit card payment.',
            'card_number.digits' => 'Card number must be exactly 16 digits.',
            'expiry_month.required' => 'Expiry month is required.',
            'expiry_month.digits' => 'Expiry month must be 2 digits.',
            'expiry_year.required' => 'Expiry year is required.',
            'expiry_year.digits' => 'Expiry year must be 4 digits.',
            'cardCVV.required' => 'CVV is required for credit card payment.',
            'cardCVV.digits_between' => 'CVV must be 3 or 4 digits.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'cash_delivery' => 'Payment Method',
            'card_number' => 'Card Number',
            'expiry_month' => 'Expiry Month',
            'expiry_year' => 'Expiry Year',
            'cardCVV' => 'CVV',
        ];
    }
}
