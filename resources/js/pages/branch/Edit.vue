<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, PropType, provide, ref } from 'vue';
import { Branch, BreadcrumbItem, Company } from '@/types';
import FormBranch from '@/components/branch/FormBranch.vue';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
        required: true,
    },
    company: {
        type: Object as PropType<Company>
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Branches', href: '/branch' },
    { title: 'Update ' + props.branch.name, href: '' },
];
const company: object = ref(props.company);
provide('company', company);

onMounted(()=>{
    //console.log('EDIT', company);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="props.branch.name" />
        <form-branch :branch="branch" />
    </Layout>
</template>
