<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, PropType, provide } from 'vue';
import { type BreadcrumbItem, Company } from '@/types';
import CompanyProfile from '@/components/company/profile/CompanyProfile.vue';
import { CompanyTranslations } from '@/types/translations';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
    isDeleted: {
        type: Boolean,
    },
    translations: {
        type: Object as PropType<CompanyTranslations>,
        required: true,
    },
});

const title = computed(() => (props.isDeleted ? props.translations?.label?.archive : props.translations?.companies));
const href = computed(() => (props.isDeleted ? '/company/archive' : '/company'));

const breadcrumbs: BreadcrumbItem[] = [
    { title: title.value, href: href.value },
    { title: props.company.name, href: '' },
];

provide('translations', props.translations);
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="company.name" />
        <CompanyProfile :company="company" />
    </Layout>
</template>
