<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { ref } from 'vue';
import Table from '@/components/user/Table.vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: '/users' }];

defineProps({
    users: {
        type: Object,
        required: true,
    },
    count: {
        type: Number,
    }
});

const total = ref();
const counter = (num: number) => {
    total.value = num;
};
</script>

<template>
    <Head title="Users" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast :pt="{
                root: {
                    class: '!max-w-max',
                },
            }"/>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Карточки клиентов на базе номеров мобильных телефонов. Всего карточек:
                {{ total || count }}
            </div>
            <div class="card">
                <Table
                    :entities="users"
                    :tools="{
                        create: true,
                        update: true,
                        remove: true,
                    }"
                    :routes="{
                    create: 'users.store',
                    update: 'users.update',
                    delete: 'users.destroy',
                    multiDestroy: 'users.multiDestroy',
                    restore: 'users.restore',
                    multiRestore: 'multiRestore',
                }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
