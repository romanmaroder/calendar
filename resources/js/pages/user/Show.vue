<script setup lang="ts">
import UserProfile from '@/components/user/profile/UserProfile.vue';
import { getFullname } from '@/composables/useFullname';
import Layout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, PropType, provide } from 'vue';
import { UserTranslations } from '@/types/translations';

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
    translations:{
        type: Object as PropType<UserTranslations>,
        required:true,
    }
});

const title = computed(() => (props.isDeleted ? props.translations?.label?.archive : props.translations?.users));
const href = computed(() => (props.isDeleted ? '/users/archive' : '/users'));


const breadcrumbs: BreadcrumbItem[] = [
    { title: title.value, href: href.value },
    { title: getFullname({ name: props.user.name, surname: props.user.surname }), href: '' },
];

provide('translations', props.translations);
provide('isDeleted', props.isDeleted);
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="props.user.surname" />
        <UserProfile :user="user" />
    </Layout>
</template>
