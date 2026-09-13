<script setup lang="ts">
import Table from '@/components/client/Table.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Client } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { PropType, provide, ref } from 'vue';
import { ClientTranslations } from '@/types/translations';

const props = defineProps({
    clients: {
        type: Object as PropType<Client>,
        required: true,
    },
    count: {
        type: Number,
    },
    translations:{
        type: Object as PropType<ClientTranslations>,
        required:true,
    }
});

const breadcrumbs: BreadcrumbItem[] = [{ title: props.translations?.title, href: '/clients' }];
provide('translations',props.translations);

const total = ref();

const counter = (num: number) => {
    total.value = num;
};
</script>

<template>
    <Head :title="translations?.title" />
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
