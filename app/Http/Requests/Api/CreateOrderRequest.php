<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', 'in:EGP,USD,EUR'],
            'customer_email' => ['required', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'The order amount is required.',
            'amount.numeric' => 'The order amount must be a number.',
            'amount.min' => 'The order amount must be at least 1.',
            'currency.required' => 'The currency is required.',
            'currency.in' => 'The currency must be one of: EGP, USD, EUR.',
            'customer_email.required' => 'The customer email is required.',
            'customer_email.email' => 'The customer email must be a valid email address.',
        ];
    }
}
