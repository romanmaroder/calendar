<script setup lang="ts">
import FormCompany from '@/components/company/FormCompany.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { provide, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Companies', href: '/company' },
    { title: 'Create company', href: '' },
];
const props = defineProps({
    countries: {
        type: Object,
    },
});

const countries: object = ref(props.countries);
provide('countries', countries);
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Create" />
        <form-company />
    </Layout>
</template>
