<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import FormUser from '@/components/user/FormUser.vue';
import { provide, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Create user', href: '' },
];

const props = defineProps({
    branch:{
        type:Array,
    }
});

// Предоставляем (provide) listOfBranches всем дочерним компонента список филиалов
const listOfBranches: object = ref(props.branch);
provide('listOfBranches', listOfBranches);

</script>
<template>
    <Head title="Create user" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-user />
    </Layout>
</template>
