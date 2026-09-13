<script setup lang="ts">
import FormUser from '@/components/user/FormUser.vue';
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType, provide, ref } from 'vue';
import { UserTranslations } from '@/types/translations';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
    },
    roles: {
        type: Array as PropType<{ id: number; name: string }[]>
    }, // <-- передаём роли из Inertia
    translations:{
        type: Object as PropType<UserTranslations>,
        required:true,
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations?.users, href: '/users' },
    { title: props.translations?.title, href: '' },
];


// Предоставляем (provide) listOfBranches всем дочерним компонента список филиалов
const listOfBranches: object = ref(props.branch);
const roles = ref(props.roles);

provide('listOfBranches', listOfBranches);
provide('roles', roles);
provide('translations', props.translations);

</script>
<template>
    <Head title="Create user" />
    <Layout :breadcrumbs="breadcrumbs">
        <form-user />
    </Layout>
</template>
