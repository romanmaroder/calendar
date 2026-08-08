<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Branch, BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { PropType, provide, ref } from 'vue';
import Table from '@/components/user/Table.vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: '/users' }];

const props = defineProps({
    users: {
        type: Object as PropType<User>,
        required: true,
    },
    branch: {
        type: Object as PropType<Branch>,
    },
    roles:{
        type: Array as PropType<{ id: number; name: string }[]>
    },
    count: {
        type: Number,
    },
});

// Предоставляем (provide) listOfBranches всем дочерним компонента список филиалов
const listOfBranches: object = ref(props.branch);
const roles = ref(props.roles);

provide('listOfBranches', listOfBranches);
provide('roles', roles);

const total = ref();
const counter = (num: number) => {
    total.value = num;
};
</script>
<template>
    <Head title="Users" />
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
                        create: true,
                        update: true,
                        show: true,
                        remove: true,
                    }"
                    @count="counter"
                />
            </div>
        </div>
    </Layout>
</template>
