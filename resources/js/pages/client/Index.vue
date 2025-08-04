<script setup lang="ts">
import Table from '@/components/client/Table.vue';
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
});

const total = ref();

const counter = (num: number) => {
    total.value = num;
};
</script>

<template>
    <Head title="Clients" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast
            :pt="{
                root: {
                    class: '!max-w-max',
                },
            }"
        />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Карточки клиентов на базе номеров мобильных телефонов. Всего карточек:
                {{ total || count }}
            </div>
            <div class="card">
                <Table
                    :entities="clients"
                    :tools="{
                        create: true,
                        update: true,
                        remove: true,
                    }"
                    :routes="{
                        create: 'clients.store',
                        show: 'clients.show',
                        update: 'clients.update',
                        delete: 'clients.destroy',
                        multiDestroy: 'multiDestroy',
                        restore: 'clients.restore',
                        multiRestore: 'multiRestore',
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
