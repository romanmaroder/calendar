<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Branch, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType } from 'vue';
import BranchProfile from '@/components/branch/profile/BranchProfile.vue';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Branches', href: '/branch' },
    { title: props.branch.name, href: '' },
];
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="branch.name" />
        <BranchProfile :branch="branch" />
    </Layout>
</template>
