<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import Table from '@/components/user/Table.vue';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Archive', href: 'users/archive' }];

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
    <Head title="Archive" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast :pt="{
                root: {
                    class: '!max-w-max',
                },
            }"/>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Всего карточек в корзине:
                {{ total || count}}
            </div>
            <div class="card">
                <Table
                    :entities="users"
                    :tools="{
                        restore: true,
                        remove: true,
                    }"
                    :routes="{
                        archive: 'archive',
                        delete: 'trash',
                        multiDestroy: 'trash',
                        restore: 'users.restore',
                        multiRestore: 'users.multiRestore',
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
