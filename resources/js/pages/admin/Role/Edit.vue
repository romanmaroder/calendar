<script setup lang="ts">
import RoleForm from '@/components/admin/role/RoleForm.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { RolesTranslations } from '@/types/translations';
import { provide, ref } from 'vue';


// Контроллер должен отдавать role, assignedPermissions, permissions
// Пример:
// $role = Role::with('permissions')->findOrFail($id);
// return inertia('admin.Role.Edit', [
//   'role' => $role->toArray(),
//   'assignedPermissions' => $role->permissions->pluck('id')->toArray(),
//   'permissions' => Permission::all(['id','name'])->toArray(),
// ]);
const props=defineProps<{
    role: { id: number; name: string };
    assignedPermissions: number[];
    permissions: Array<{ id: number; name: string }>;
    translations: RolesTranslations;
}>();


const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.roles, href: '/admin/roles' },
    { title: props.translations.title, href: '' },
];


const formTranslations = ref({
    button: props.translations.button,
});

provide('formTranslations', formTranslations);

</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Редактировать роль" />
        <div class="sm:mx-auto sm:w-lg p-4">
            <RoleForm :role="role" :assigned-permissions="assignedPermissions" :permissions="permissions" @submit-success="() => {}" />
        </div>
    </Layout>
</template>
