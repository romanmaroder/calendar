<script setup lang="ts">
import { getFullname } from '@/composables/useFullname';
import Layout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, PropType } from 'vue';
import UserProfile from '@/components/user/profile/UserProfile.vue';

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
    count: {
        type: Number,
    },
    isDeleted: {
        type: Boolean,
    },
});

const title = computed(()=>props.isDeleted ? 'Archive': 'Users');
const href = computed(()=>props.isDeleted ? '/users/archive':'/users');

const breadcrumbs: BreadcrumbItem[] = [
    { title: title.value, href: href.value },
    { title: getFullname({ name: props.user.name, surname: props.user.surname }), href: '' },
];
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="props.user.surname" />
        <UserProfile :user="user" />
    </Layout>
</template>
