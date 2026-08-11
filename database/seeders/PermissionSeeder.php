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
            'companies.view',
            'companies.create',
            'companies.edit',
            'companies.delete',
            'companies.force-delete',
            'companies.restore',
            'branches.view',
            'branches.create',
            'branches.edit',
            'branches.delete',
            'branches.force-delete',
            'branches.restore',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.force-delete',
            'users.restore',
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.force-delete',
            'clients.restore',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        // Роли (roles)
        $adminRole = Role::findOrCreate('admin', 'web');
        $managerRole = Role::findOrCreate('manager', 'web');
        $masterRole = Role::findOrCreate('master', 'web');

        // Права для админа (всё)
        $adminRole->givePermissionTo(Permission::all());

        // Права для менеджера
        $managerRole->givePermissionTo([
                                           'companies.view',
                                           'branches.view',
                                           'users.view',
                                           'users.create',
                                           'users.edit',
                                           'users.delete',
                                           'clients.view',
                                           'clients.create',
                                           'clients.edit',
                                           'clients.delete',
                                           'services.view',
                                           'services.edit',
                                           'services.delete',
                                       ]);

        // Права для мастера
        $masterRole->givePermissionTo([
                                          'companies.view',
                                          'branches.view',
                                          'users.view', // может видеть список мастеров/сотрудников
                                          'clients.view',
                                          'services.view',
                                      ]);
    }
}
