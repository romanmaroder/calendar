<script setup lang="ts">
import ClientFilter from '@/components/client/ClientFilter.vue';
import ClientTable from '@/components/client/ClientTable.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Clients', href: '/clients' }];

 defineProps({
    clients: {
        type: Object,
        required: true,
    },
    count: {
        type: Number,
    },
});
</script>

<template>
    <Head title="Clients" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Карточки клиентов на базе номеров мобильных телефонов. Всего карточек:
                {{ count }}
            </div>
            <ClientFilter :clients="clients" />

            <ClientTable :clients="clients" />
        </div>
    </Layout>
</template>
