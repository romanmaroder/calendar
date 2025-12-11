<script setup lang="ts">
import FormUser from '@/components/user/FormUser.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Update user', href: '' },
];

defineProps({
    user: Object as PropType<User | null>,
    branch: {
        type: Array,
    }
});
</script>

<template>
    <Head title="Edit user" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-user :user="user"></form-user>
    </Layout>
</template>
