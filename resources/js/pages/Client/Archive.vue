<script setup lang="ts">
import DataTable from '@/components/tables/user/DataTable.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Archive', href: '/archive' }];

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
        type: Object,
    },
});

const total = ref();
const counter = (num: number) => {
    total.value = num;
};
</script>

<template>
    <Head title="Archive" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Всего карточек в корзине:
                {{ total }}
            </div>
            <DataTable :entities="clients" :columns="columns"  restore tools remove :filters-fields="filters" @count="counter" />
        </div>
    </Layout>
</template>
