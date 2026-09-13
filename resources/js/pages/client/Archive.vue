<script setup lang="ts">
import Table from '@/components/client/Table.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Client } from '@/types';
import { ClientTranslations } from '@/types/translations';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { PropType, provide, ref } from 'vue';

const props=defineProps({
    clients: {
        type: Object as PropType<Client>,
        required: true,
    },
    count: {
        type: Number,
    },
    translations: {
        type: Object as PropType<ClientTranslations>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.title, href: '/clients' },
    { title: props.translations.label.archive, href: '' },
];

const total = ref();
const counter = (num: number) => {
    total.value = num;
};
provide('translations',props.translations);

</script>

<template>
    <Head :title="translations?.label?.archive" />
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
                        multiRestore: 'multiRestore'
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
