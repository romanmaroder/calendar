<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Layout from '@/layouts/AppLayout.vue';
import PermissionForm from '@/components/admin/permission/PermissionForm.vue';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Permissions', href: '/admin/permissions' },
    { title: 'Edit permission', href: '' },
];

defineProps<{ permission: { id: number; name: string } }>();
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Редактировать разрешение" />
        <div class="sm:mx-auto sm:w-lg p-4">
            <PermissionForm :permission="permission" @submit-success="() => {}" />
        </div>
    </Layout>
</template>
