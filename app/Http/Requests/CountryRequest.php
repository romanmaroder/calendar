<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|size:2|regex:/^[A-Z]{2}$/',
            'name' => 'required|string|max:100',
            'iso_code' => 'nullable|string|size:3|regex:/^[A-Z]{3}$/',
            'phone_code' => 'nullable|string|regex:/^\+\d{1,3}$/',
            'phone_regex' => 'required|string|regex:/^\/.*\/$/',
            'currency' => 'nullable|string|size:3|regex:/^[A-Z]{3}$/',
            'active' => 'boolean',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
