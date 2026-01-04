<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem } from '@/types';
import Table from '@/components/branch/Table.vue';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { PropType, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] =
    [{ title: 'Branches', href: '/branch' },{title:'Archive',href:''}];

defineProps({
    branches: {
        type: Object as PropType<Branch>,
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
    <Layout :breadcrumbs="breadcrumbs">
    <Head title="Archive" />
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
                    :branches="branches"
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
