<?php

namespace App\Http\Requests\Branch;

use App\Rules\PhoneByCountryRegex;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:250',
            'description' => 'nullable|string|min:3|max:550',
            'contact' => 'nullable|string|min:3|max:250',
            'avatar' => 'nullable|string',
            'status' => 'boolean',
            'country_id' => 'required|exists:countries,id',
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
