<script setup lang="ts">
import FormClient from '@/components/client/FormClient.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Client } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType } from 'vue';

const props =defineProps({
    client: {
        type: Object as PropType<Client>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Clients', href: '/clients' },
    { title: 'Update ' +props.client.surname, href: '' },
];

</script>

<template>
    <Head :title="props.client.surname" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-client :client="client" />
    </Layout>
</template>
