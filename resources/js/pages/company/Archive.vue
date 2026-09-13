<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Company } from '@/types';
import { PropType, provide, ref } from 'vue';
import Toast from 'primevue/toast';
import Table from '@/components/company/Table.vue';
import { CompanyTranslations } from '@/types/translations';

const props =defineProps({
    companies: {
        type: Object as PropType<Company>,
        required: true,
    },
    count: {
        type: Number,
    },
    translations:{
        type: Object as PropType<CompanyTranslations>,
        required: true,
    }
});


const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.title, href: '/company' },
    { title: props.translations.label.archive, href: '' },
];

provide('translations', props.translations);



const total = ref();
const counter = (num: number) => {
    total.value = num;
};
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations.label.archive" />
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
