<script setup lang="ts">
import ClientProfile from '@/components/client/profile/ClientProfile.vue';
import { getFullname } from '@/composables/useFullname';
import Layout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Client } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, PropType } from 'vue';

const props = defineProps({
    client: {
        type: Object as PropType<Client>,
        required: true,
    },
    count: {
        type: Number,
    },
    isDeleted: {
        type: Boolean,
    },
});

const title = computed(() => (props.isDeleted ? 'Archive' : 'Clients'));
const href = computed(() => (props.isDeleted ? '/clients/archive' : '/clients'));

const breadcrumbs: BreadcrumbItem[] = [
    { title: title.value, href: href.value },
    { title: getFullname({ name: props.client.name, surname: props.client.surname }), href: '' },
];

</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="props.client.surname" />
        <ClientProfile :client="client" />
    </Layout>
</template>
