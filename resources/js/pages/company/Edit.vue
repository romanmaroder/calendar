<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Company } from '@/types';
import FormCompany from '@/components/company/FormCompany.vue';
import { PropType, provide, ref } from 'vue';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
    countries: {
        type: Object
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Companies', href: '/company' },
    { title: 'Update ' + props.company.name, href: '' },
];

const countries: object = ref(props.countries);
provide('countries', countries);
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="props.company.name" />
        <form-company :company="company" />
    </Layout>
</template>
