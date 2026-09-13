<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Layout from '@/layouts/AppLayout.vue';
import PermissionForm from '@/components/admin/permission/PermissionForm.vue';
import { BreadcrumbItem } from '@/types';
import { PermissionTranslations } from '@/types/translations';
import { provide, ref } from 'vue';

const props =defineProps<{ permission: { id: number; name: string };translations: PermissionTranslations; }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations.permissions, href: '/admin/permissions' },
    { title: props.translations.title, href: '' },
];


const formTranslations = ref({
    button: props.translations.button,
    placeholder: props.translations.placeholder,
    toast:props.translations.toast
});

provide('formTranslations', formTranslations);

</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head :title="translations.title" />
        <div class="card">
            <Message closable severity="warn" icon="pi pi-exclamation-triangle">{{translations.message}}</Message>
        </div>
        <div class="sm:mx-auto sm:w-lg p-4">
            <PermissionForm :permission="permission" @submit-success="() => {}" />
        </div>
    </Layout>
</template>
