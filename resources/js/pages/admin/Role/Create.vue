<script setup lang="ts">
import RoleForm from '@/components/admin/role/RoleForm.vue'; // путь под свой проект
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { RolesTranslations } from '@/types/translations';
import { provide, ref } from 'vue';

// В Laravel контроллере нужно отдавать permissions и пустой role/assignedPermissions
// Пример: return inertia('admin.Role.Create', [
//   'permissions' => Permission::all(['id', 'name'])->toArray(),
// ]);
const props = defineProps<{
    permissions: Array<{ id: number; name: string }>;
    translations: RolesTranslations;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.roles, href: '/admin/roles' },
    { title: props.translations.title, href: '' },
];

const formTranslations = ref({
    button: props.translations.button,
    placeholder: props.translations.placeholder,
});

provide('formTranslations', formTranslations);

</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Создать роль" />
        <div class="p-4 sm:mx-auto sm:w-lg">
            <RoleForm :permissions="permissions" @submit-success="() => {}" />
        </div>
    </Layout>
</template>
