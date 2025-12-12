<script setup lang="ts">
import FormUser from '@/components/user/FormUser.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';

const props =defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
    branch: {
        type: Array,
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Update ' +props.user.surname, href: '' },
];

// Предоставляем (provide) listOfBranches всем дочерним компонента список филиалов
const listOfBranches: object = ref(props.branch);
provide('listOfBranches', listOfBranches);

</script>

<template>
    <Head :title="props.user.surname" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-user :user="user"></form-user>
    </Layout>
</template>
