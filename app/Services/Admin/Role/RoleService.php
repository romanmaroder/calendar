<?php

namespace App\Services\Admin\Role;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function create(array $data): Role
    {
        /**
         * $data гарантированно валиден:
         * - name: string, не пустой, уникальный
         * - permission_ids: null или array of int
         */
        $role = Role::create(['name' => $data['name']]);

        if (! empty($data['permission_ids'])) {
            $role->givePermissionTo($data['permission_ids']);
        }

        return $role;
    }

    public function update(Role $role, array $data): void
    {
        /**
         * $data гарантированно валиден.
         * Имя уже уникально при изменении.
         */
        $role->update(['name' => $data['name']]);

        // syncPermissions удалит лишние и добавит новые
        $permissionIds = $data['permission_ids'] ?? [];
        $role->syncPermissions($permissionIds);
    }
}