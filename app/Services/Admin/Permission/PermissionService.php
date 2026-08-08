<?php

namespace App\Services\Admin\Permission;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    /**
     * @throws \Throwable
     */
    public function create(array $data): Permission
    {
        return DB::transaction(function () use ($data) {
            return Permission::create([
                                          'name'       => $data['name'],
                                          'guard_name' => $data['guard_name'] ?? 'web',
                                      ]);
        });
    }

    /**
     * @throws \Throwable
     */
    public function update(Permission $permission, array $data): Permission
    {
        return DB::transaction(function () use ($permission, $data) {
            $permission->update([
                                    'name'       => $data['name'],
                                    'guard_name' => $data['guard_name'] ?? $permission->guard_name,
                                ]);

            return $permission->refresh();
        });
    }

    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}
