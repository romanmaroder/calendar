<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class UpdateRoleRequest extends FormRequest
{
    private Role $role;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }
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
        $role = $this->route('role');
        if (! $role instanceof Role) {
            throw new \InvalidArgumentException('Role not found in route.');
        }
        $this->role = $role;

        $rules = [
            'name'           => ['required', 'string', 'max:50'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ];

        // Уникальность только если имя изменилось
        if ($this->name !== $this->role->name) {
            $rules['name'][] = 'unique:roles,name';
        }

        return $rules;
    }
    protected function prepareForValidation(): void
    {
        if ($this->has('permission_ids') && $this->permission_ids === null) {
            $this->merge(['permission_ids' => []]);
        }
    }

}
