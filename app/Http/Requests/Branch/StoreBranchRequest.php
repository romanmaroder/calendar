<?php

namespace App\Http\Requests\Branch;

use App\Rules\PhoneByCountryRegex;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
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
            'contact' => 'required|string|min:3|max:250',
            'avatar' => 'nullable|string',
            'status' => 'boolean',
            'company_id' => 'required|exists:companies,id',
            'resolved_country_id' => 'required|exists:countries,id',
        ];
        // Добавляем правила для phone только если resolved_country_id валиден
        if ($this->filled('resolved_country_id')) {
            $rules['phone'] = [
                'required',
                new PhoneByCountryRegex($this->input('resolved_country_id'))
            ];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'company_id.required' => '«Компания» обязательно для заполнения.',
            'resolved_country_id.required' => '«Страна» обязательно для заполнения.',
            'name.required' => '«Имя» обязательно для заполнения.',
            'contact.required' => '«Контакты» обязательно для заполнения.',
            'phone.required' => '«Телефон» обязательно для заполнения.',
        ];
    }
}
