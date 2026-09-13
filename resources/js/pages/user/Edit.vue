<script setup lang="ts">
import FormUser from '@/components/user/FormUser.vue';
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';
import { UserTranslations } from '@/types/translations';

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
    branches: {
        type: Object as PropType<Branch>,
    },
    roles: {
        type: Array as PropType<{ id: number; name: string }[]>,
    }, // <-- передаём роли из Inertia
    translations:{
        type: Object as PropType<UserTranslations>,
        required:true,
    }
});


const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations?.users, href: '/users' },
    { title: props.translations?.title + props.user.surname, href: '' },
];

// Предоставляем (provide) listOfBranches всем дочерним компонента список филиалов
const listOfBranches: object = ref(props.branches);
const roles = ref(props.roles);

provide('translations', props.translations);
provide('listOfBranches', listOfBranches);
provide('roles', roles);

</script>

<template>
    <Head :title="props.user.surname" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-user :user="user"></form-user>
    </Layout>
</template>
