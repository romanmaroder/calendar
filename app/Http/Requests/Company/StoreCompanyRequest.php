<?php

namespace App\Http\Requests\Company;

use App\Models\Company\Company;
use App\Rules\PhoneByCountryRegex;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'contact' => 'required|string|max:255',
            'info' => 'nullable|string|max:500',
            'avatar' => 'nullable|string',
        ];

        // Добавляем правила для phone только если country_id валиден
        if ($this->filled('country_id')) {
            $rules['phone'] = [
                'required',
                new PhoneByCountryRegex($this->input('country_id'))
            ];
        }

        return $rules;

    }
}
