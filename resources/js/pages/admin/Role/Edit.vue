<script setup lang="ts">
import RoleForm from '@/components/admin/role/RoleForm.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: '/admin/roles' },
    { title: 'Edit role', href: '' },
];

// Контроллер должен отдавать role, assignedPermissions, permissions
// Пример:
// $role = Role::with('permissions')->findOrFail($id);
// return inertia('admin.Role.Edit', [
//   'role' => $role->toArray(),
//   'assignedPermissions' => $role->permissions->pluck('id')->toArray(),
//   'permissions' => Permission::all(['id','name'])->toArray(),
// ]);
defineProps<{
    role: { id: number; name: string };
    assignedPermissions: number[];
    permissions: Array<{ id: number; name: string }>;
}>();
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Редактировать роль" />
        <div class="sm:mx-auto sm:w-lg p-4">
            <RoleForm :role="role" :assigned-permissions="assignedPermissions" :permissions="permissions" @submit-success="() => {}" />
        </div>
    </Layout>
</template>
