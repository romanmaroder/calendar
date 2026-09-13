<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';
import { Branch, BreadcrumbItem, Company } from '@/types';
import FormBranch from '@/components/branch/FormBranch.vue';
import { BranchTranslations } from '@/types/translations';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
        required: true,
    },
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
    { title: props.translations.title + props.branch.name, href: '' },
];

const translations = ref(props.translations);
provide('translations', translations);

const companies: object = ref(props.companies);
provide('companies', companies);
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="props.branch.name" />
        <form-branch :branch="branch" />
    </Layout>
</template>
