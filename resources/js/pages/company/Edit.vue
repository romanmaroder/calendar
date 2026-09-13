<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Company } from '@/types';
import FormCompany from '@/components/company/FormCompany.vue';
import { PropType, provide, ref } from 'vue';
import { CompanyTranslations } from '@/types/translations';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
    countries: {
        type: Object,
    },
    translations: {
        type: Object as PropType<CompanyTranslations>,
        required: true,
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.companies, href: '/company' },
    { title: props.translations.title + props.company.name, href: '' },
];

const translations = ref(props.translations);
provide('translations', translations);


const countries: object = ref(props.countries);
provide('countries', countries);
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations.title" />
        <form-company :company="company" />
    </Layout>
</template>
