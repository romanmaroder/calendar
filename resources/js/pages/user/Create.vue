<script setup lang="ts">
import FormUser from '@/components/user/FormUser.vue';
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Create user', href: '' },
];

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
    },
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
