<script setup lang="ts">
import ClientTable from '@/components/client/ClientTable.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Clients', href: '/clients' }];

const props = defineProps({
    clients: {
        type: Object,
        required: true,
    },
    count: {
        type: Number,
    }
});

const items = ref();
const total = ref();

onMounted(() => {
    items.value = props.clients.data;
});
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
                <ClientTable
                    :entities="clients"
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
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
