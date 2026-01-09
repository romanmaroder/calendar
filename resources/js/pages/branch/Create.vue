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
    countries:{
        type:Object,
    },
    user: {
        type: Array,
    },
});
const countries: object = ref(props.countries);
provide('countries', countries);

onMounted(()=>{
    console.log(countries);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs" >
        <Head title="Create" />
        <form-branch/>
    </Layout>
</template>
