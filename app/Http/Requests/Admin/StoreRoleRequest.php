<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:50', 'unique:roles,name'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ];
    }
    protected function prepareForValidation(): void
    {
        // Если фронтенд может прислать null вместо пустого массива — нормализуем
        if ($this->has('permission_ids') && $this->permission_ids === null) {
            $this->merge(['permission_ids' => []]);
        }
    }

}
