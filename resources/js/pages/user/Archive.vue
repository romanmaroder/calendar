<script setup lang="ts">
import Table from '@/components/user/Table.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { PropType, provide, ref } from 'vue';
import { UserTranslations } from '@/types/translations';


const props = defineProps({
    users: {
        type: Object as PropType<User>,
        required: true,
    },
    count: {
        type: Number,
    },
    translations:{
        type: Object as PropType<UserTranslations>,
        required: true,
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: props.translations?.title, href: '/users' },
    { title: props.translations?.label?.archive, href: '' },
];

provide('translations', props.translations);

const total = ref();
const counter = (num: number) => {
    total.value = num;
};
</script>

<template>
    <Head :title="translations?.label?.archive" />
    <Layout :breadcrumbs="breadcrumbs">
        <Toast
            :pt="{
                root: {
                    class: '!max-w-max',
                },
            }"
        />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="card">
                <Table
                    :entities="users"
                    :tools="{
                        restore: true,
                        remove: true,
                        show: true,
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
