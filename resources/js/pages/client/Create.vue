<script setup lang="ts">
import FormClient from '@/components/client/FormClient.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide } from 'vue';
import { ClientTranslations } from '@/types/translations';

const props = defineProps({
    translations: {
        type: Object as PropType<ClientTranslations>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.clients, href: '/clients' },
    { title: props.translations.title, href: '' },
];

provide('translations', props.translations);
</script>

<template>
    <Head :title="translations?.title" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-client />
    </Layout>
</template>
