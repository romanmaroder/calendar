<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, PropType } from 'vue';
import { BreadcrumbItem, Company } from '@/types';
import Table from '@/components/company/Table.vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Company', href: '/company' }];

defineProps({
    companies: {
        type: Object as PropType<Company>,
        required: true,
    },
    countries: {
        type: Object,
    },
});

onMounted(() => {
    //console.log(props.companies);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Company" />
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
                    :companies="companies"
                    :tools="{
                        create: true,
                        update: true,
                        show: true,
                        remove: true,
                    }"
                />
            </div>
        </div>
    </Layout>
</template>
