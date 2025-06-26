<script setup lang="ts">
import Filter from '@/components/filters/user/Filter.vue';
import DataTable from '@/components/tables/user/DataTable.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Clients', href: '/clients' }];

defineProps({
    clients: {
        type: Object,
        required: true,
    },
    count: {
        type: Number,
    },
    columns: {
        type: Object,
    },
    filters: {
        type:Object,
    }
});

const total = ref();
const counter = (num: number) => {
    total.value = num;
};
</script>

<template>
    <Head title="Clients" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Карточки клиентов на базе номеров мобильных телефонов. Всего карточек:
                {{ total }}
            </div>
            <Filter :entities="clients" url-to-refresh="clients.index" />
            <DataTable :entities="clients" :columns="columns" :filtersFields="filters" create tools remove update
                       @count="counter" />
        </div>
    </Layout>
</template>
