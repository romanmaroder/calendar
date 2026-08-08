<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Разрешения (permissions)
        $permissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.manage',
            'permissions.view',
            'permissions.manage',
            'services.view',
            'services.edit',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate( $name, 'web');
        }

        // Роли (roles)
        $adminRole = Role::findOrCreate('admin',  'web');
        $managerRole = Role::findOrCreate('manager', 'web');
        $masterRole = Role::findOrCreate('master', 'web');

        // Права для админа (всё)
        $adminRole->givePermissionTo(Permission::all());

        // Права для менеджера
        $managerRole->givePermissionTo([
                                           'users.view',
                                           'users.edit',
                                           'users.delete',
                                           'services.view',
                                           'services.edit',
                                           'roles.view',
                                       ]);

        // Права для мастера
        $masterRole->givePermissionTo([
                                          'users.view', // может видеть список мастеров/сотрудников
                                          'services.view',
                                      ]);
    }
}
