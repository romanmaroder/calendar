<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Company } from '@/types';
import Table from '@/components/company/Table.vue';
import { CompanyTranslations } from '@/types/translations';
import { PropType, provide, ref } from 'vue';

const props = defineProps({
    companies: {
        type: Object as PropType<Company>,
        required: true,
    },
    countries: {
        type: Object,
    },
    translations: {
        type: Object as PropType<CompanyTranslations>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [{ title: props.translations?.title, href: '/company' }];

provide('translations', props.translations);

const countries: object = ref(props.countries);
provide('countries', countries);

//onMounted(()=>{console.log('INDEX',tableTranslations)});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations.title" />
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
