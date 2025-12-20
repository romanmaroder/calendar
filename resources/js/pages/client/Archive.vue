<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Client } from '@/types';
import Table from '@/components/client/Table.vue';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { PropType, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Clients', href: '/clients' },{ title: 'Archive', href: '' }];

defineProps({
    clients: {
        type: Object as PropType<Client>,
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
    <Head title="Archive" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast
            :pt="{
                root: {
                    class: '!max-w-max',
                },
            }"
        />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="card">
                <Table
                    :entities="clients"
                    :tools="{
                        restore: true,
                        remove: true,
                    }"
                    :routes="{
                        archive: 'archive',
                        delete: 'clients.trash',
                        multiDestroy: 'trash',
                        restore: 'clients.restore',
                        multiRestore: 'multiRestore',
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
