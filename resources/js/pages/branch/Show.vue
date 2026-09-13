<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Branch, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, PropType, provide } from 'vue';
import BranchProfile from '@/components/branch/profile/BranchProfile.vue';
import { BranchTranslations } from '@/types/translations';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
        required: true,
    },
    isDeleted: {
        type: Boolean,
    },
    translations: {
        type: Object as PropType<BranchTranslations>,
        required: true,
    },
});

const title = computed(() => (props.isDeleted ? props.translations?.label?.archive : props.translations?.branches));
const href = computed(() => (props.isDeleted ? '/branch/archive' : '/branch'));

const breadcrumbs: BreadcrumbItem[] = [
    { title: title.value, href: href.value  },
    { title: props.branch.name, href: '' },
];

const translations = props.translations;
provide('translations', translations);
provide('isDeleted', props.isDeleted);
onMounted(() => {
    console.log('SHOW-PAGE-BRANCH', props.branch);
});
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="branch.name" />
        <BranchProfile :branch="branch" />
    </Layout>
</template>
