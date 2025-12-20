<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
import FormBranch from '@/components/branch/FormBranch.vue';


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Branches', href: '/branch' },
    { title: 'Create branch', href: '' },
];

defineProps({
    user: {
        type: Array,
    },
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs" >
        <Head title="Create" />
        <form-branch/>
    </Layout>
</template>
