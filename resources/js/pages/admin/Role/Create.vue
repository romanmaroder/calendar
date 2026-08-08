<script setup lang="ts">
import RoleForm from '@/components/admin/role/RoleForm.vue'; // путь под свой проект
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: '/admin/roles' },
    { title: 'Create role', href: '' },
];

// В Laravel контроллере нужно отдавать permissions и пустой role/assignedPermissions
// Пример: return inertia('admin.Role.Create', [
//   'permissions' => Permission::all(['id', 'name'])->toArray(),
// ]);
defineProps<{
    permissions: Array<{ id: number; name: string }>;
}>();
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
    <Head title="Создать роль" />
        <div class="sm:mx-auto sm:w-lg p-4">
            <RoleForm :permissions="permissions" @submit-success="() => {}" />
        </div>
    </Layout>
</template>
