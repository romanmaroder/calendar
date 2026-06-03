<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
import FormBranch from '@/components/branch/FormBranch.vue';
import { onMounted, provide, ref } from 'vue';


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Branches', href: '/branch' },
    { title: 'Create branch', href: '' },
];

const props = defineProps({
    company:{
        type:Object,
    },
    user: {
        type: Array,
    },
});
const company: object = ref(props.company);
provide('company', company);

onMounted(()=>{
    //console.log(props.countries);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs" >
        <Head title="Create" />
        <form-branch/>
    </Layout>
</template>
