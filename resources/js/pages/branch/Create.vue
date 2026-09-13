<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Company } from '@/types';
import FormBranch from '@/components/branch/FormBranch.vue';
import { PropType, provide, ref } from 'vue';
import { BranchTranslations } from '@/types/translations';

const props = defineProps({
    companies: {
        type: Object as PropType<Company>,
    },
    translations: {
        type: Object as PropType<BranchTranslations>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.branches, href: '/branch' },
    { title: props.translations.title, href: '' },
];

const translations = ref(props.translations);
provide('translations', translations);

const companies: object = ref(props.companies);
provide('companies', companies);

</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations.title" />
        <form-branch />
    </Layout>
</template>
