<script setup lang="ts">
import FormClient from '@/components/client/FormClient.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Client } from '@/types';
import { ClientTranslations } from '@/types/translations';
import { Head } from '@inertiajs/vue3';
import { PropType, provide } from 'vue';

const props = defineProps({
    client: {
        type: Object as PropType<Client>,
        required: true,
    },
    translations: {
        type: Object as PropType<ClientTranslations>,
        required: true,
    },
});


const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.clients, href: '/clients' },
    { title: props.translations.title + props.client.surname, href: '' },
];

provide('translations',props.translations);
</script>

<template>
    <Head :title="props.client.surname" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-client :client="client" />
    </Layout>
</template>
