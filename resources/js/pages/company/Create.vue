<script setup lang="ts">
import FormCompany from '@/components/company/FormCompany.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';
import { CompanyTranslations } from '@/types/translations';

const props = defineProps({
    countries: {
        type: Object,
    },
    translations: {
        type: Object as PropType<CompanyTranslations>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.companies, href: '/company' },
    { title: props.translations.title, href: '' },
];

const translations = ref(props.translations);
provide('translations', translations);

const countries: object = ref(props.countries);
provide('countries', countries);


</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations.title" />
        <form-company />
    </Layout>
</template>
