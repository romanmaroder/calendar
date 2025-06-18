<script setup lang="ts">
import ClientArchiveTable from '@/components/client/ClientArchiveTable.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Archive', href: '/archive' }];

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
    <Head title="Archive" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Карточки клиентов на базе номеров мобильных телефонов. Всего карточек:
                {{ count }}
            </div>
            <ClientArchiveTable :clients="clients" :count="count" />
        </div>
    </Layout>
</template>