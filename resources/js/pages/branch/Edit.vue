<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { PropType } from 'vue';
import { Branch, BreadcrumbItem } from '@/types';
import FormBranch from '@/components/branch/FormBranch.vue';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Branches', href: '/branch' },
    { title: 'Update ' + props.branch.name, href: '' },
];
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="props.branch.name" />
        <form-branch :branch="branch" />
    </Layout>
</template>
