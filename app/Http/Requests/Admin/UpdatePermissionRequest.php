<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Permission $permission */
        $permission = $this->route('permission');

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:permissions,name,' . $permission->id,
            ],
        ];
    }
}
