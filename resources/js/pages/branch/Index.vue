<script setup lang="ts">
import Table from '@/components/branch/Table.vue';
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Branches', href: '/branch' }];

const props = defineProps({
    branches: {
        type: Object as PropType<Branch>,
    },
    count: {
        type: Number,
    },
    countries: {
        type: Object,
    },
});

const total = ref();
const counter = (num: number) => {
    total.value = num;
};

const countries: object = ref(props.countries);
provide('countries', countries);
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Branches" />
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
                        create: true,
                        update: true,
                        show: true,
                        remove: true,
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
