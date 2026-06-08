<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Rules\PhoneByCountryRegex;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'avatar' => 'nullable|string',
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:255',
            'birthday' => 'nullable|string|max:255',
            'branch_id' => 'required|integer',
            'email' => 'nullable|string|lowercase|email|max:255|unique:' . User::class,
            'resolved_country_id' => 'required|exists:countries,id',
        ];
        // Добавляем правила для phone только если country_id валиден
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
            'branch_id.required' => '«Филиал» обязательно для заполнения.',
            'resolved_country_id.required' => '«Страна» обязательно для заполнения.',
            'name.required' => '«Имя» обязательно для заполнения.',
            'phone.required' => '«Телефон» обязательно для заполнения.',
        ];
    }
}
