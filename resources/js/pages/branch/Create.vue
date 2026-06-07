<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Company } from '@/types';
import FormBranch from '@/components/branch/FormBranch.vue';
import { onMounted, PropType, provide, ref } from 'vue';


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Branches', href: '/branch' },
    { title: 'Create branch', href: '' },
];

const props = defineProps({
    companies:{
        type:Object as PropType<Company>,
    },
});
const companies: object = ref(props.companies);
provide('companies', companies);

onMounted(()=>{
    console.log(props.companies);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs" >
        <Head title="Create" />
        <form-branch/>
    </Layout>
</template>
