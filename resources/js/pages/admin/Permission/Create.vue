<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Layout from '@/layouts/AppLayout.vue';
import PermissionForm from '@/components/admin/permission/PermissionForm.vue';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Permissions', href: '/admin/permissions' },
    { title: 'Create permission', href: '' },
];
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Создать разрешение" />
        <div class="sm:mx-auto sm:w-lg p-4">
            <PermissionForm @submit-success="() => {}" />
        </div>
    </Layout>
</template>
