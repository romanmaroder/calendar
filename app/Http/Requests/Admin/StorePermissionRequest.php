<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Можно вынести проверку прав в middleware, здесь достаточно true
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:permissions,name',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
                         'guard_name' => 'web', // можно вынести в конфиг или передавать из формы
                     ]);
    }
}
