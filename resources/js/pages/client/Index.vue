<script setup lang="ts">
import Filter from '@/components/filters/user/Filter.vue';
import DataTable from '@/components/client/DataTable.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import {  ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Clients', href: '/clients' }];

const props = defineProps({
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
    errors:{
        type: Object,
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
            <Filter :entities="clients" :route="{index:'clients.index'}" />
            <DataTable
                :entities="clients"
                :columns="columns"
                :filtersFields="filters"
                :tools="{
                    create: true,
                    update: true,
                    remove: true,
                }"
                :routes="{
                    create: 'clients.store',
                    update: 'clients.update',
                    delete: 'clients.destroy',
                    multiDestroy: 'multiDestroy',
                    restore: 'clients.restore',
                    multiRestore: 'multiRestore',
                }"
                :errors="errors"
                @count="counter"
            />
        </div>
    </Layout>
</template>
