<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { PropType } from 'vue';
import { type BreadcrumbItem, Company } from '@/types';
import CompanyProfile from '@/components/company/profile/CompanyProfile.vue';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Companies', href: '/company' },
    { title: props.company.name, href: '' },
];
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="company.name" />
        <CompanyProfile :company="company" />
    </Layout>
</template>
