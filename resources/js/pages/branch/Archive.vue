<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem } from '@/types';
import Table from '@/components/branch/Table.vue';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { onMounted, PropType, provide, ref } from 'vue';
import { BranchTranslations } from '@/types/translations';

const props = defineProps({
    branches: {
        type: Object as PropType<Branch>,
        required: true,
    },
    count: {
        type: Number,
    },
    translations: {
        type: Object as PropType<BranchTranslations>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations?.title, href: '/branch' },
    { title: props.translations?.label.archive, href: '' },
];

provide('translations', props.translations);


const total = ref();
const counter = (num: number) => {
    total.value = num;
};

onMounted(() => {
    console.log(props.translations);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations?.label?.archive" />
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
