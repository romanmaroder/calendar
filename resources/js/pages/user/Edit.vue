<script setup lang="ts">
import FormUser from '@/components/user/FormUser.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Update user', href: '' },
];

const props =defineProps({
    user: Object as PropType<User | null>,
    branch: {
        type: Array,
    }
});

// Предоставляем (provide) listOfBranches всем дочерним компонента список филиалов
const listOfBranches: object = ref(props.branch);
provide('listOfBranches', listOfBranches);

</script>

<template>
    <Head title="Edit user" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-user :user="user"></form-user>
    </Layout>
</template>
