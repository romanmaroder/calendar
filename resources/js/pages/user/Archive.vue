<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, User } from '@/types';
import Table from '@/components/user/Table.vue';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { PropType, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: '/users' },{ title: 'Archive', href: '' }];

defineProps({
    users: {
        type: Object as PropType<User>,
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
        <Toast
            :pt="{
                root: {
                    class: '!max-w-max',
                },
            }"
        />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="client-cards-description">
                Всего карточек в корзине:
                {{ total || count }}
            </div>
            <div class="card">
                <Table
                    :entities="users"
                    :tools="{
                        restore: true,
                        remove: true,
                        show: true,
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
