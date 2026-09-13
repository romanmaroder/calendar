<script setup lang="ts">
import Table from '@/components/branch/Table.vue';
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem, Company } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted, PropType, provide, ref } from 'vue';
import { BranchTranslations } from '@/types/translations';

const props = defineProps({
    branches: {
        type: Object as PropType<Branch>,
    },
    count: {
        type: Number,
    },
    companies: {
        type: Object as PropType<Company>,
    },
    translations: {
        type: Object as PropType<BranchTranslations>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [{ title: props.translations?.title, href: '/branch' }];

provide('translations', props.translations);

const companies: object = ref(props.companies);
provide('companies', companies);

const total = ref();
const counter = (num: number) => {
    total.value = num;
};
onMounted(() => {
    //console.log('INDEX-PAGE-BRANCHES', props.branches);
    //console.log('INDEX-PAGE-COMPANIES', props.companies);
    console.log('INDEX-PAGE-TR', props.translations);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations?.title" />
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
