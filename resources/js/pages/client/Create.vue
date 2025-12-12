<script setup lang="ts">
import FormClient from '@/components/client/FormClient.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Clients', href: '/clients' },
    { title: 'Create client', href: '' },
];
</script>

<template>
    <Head title="Create" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-client />
    </Layout>
</template>
