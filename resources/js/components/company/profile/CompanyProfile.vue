<script setup lang="ts">
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Company } from '@/types';
import { computed, PropType, ref } from 'vue';
import InfoCard from './InfoCard.vue';
import ProfileCard from './ProfileCard.vue';
import BranchCard from '@/components/company/profile/BranchCard.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
});

const page = usePage();

const hasPermission = (permission: string) => {
    const userPermissions = page.props.auth?.user?.permissions ?? [];
    return userPermissions.includes(permission);
};

const items = ref([
    {
        label: 'Edit',
        icon: 'pi pi-pencil',
        permission: 'companies.edit',
        command: () => {
            try {
                window.location.href = route('company.edit', props.company.id);
            } catch (error) {
                console.error('Маршрут не найден:', error);
            }
        },
    },
    {
        label: 'Companies',
        icon: 'pi pi-building',
        permission: null,
        command: () => {
            try {
                window.location.href = route('company.index');
            } catch (error) {
                console.error('Маршрут не найден:', error);
            }
        },
    },
]);

// Фильтруем: показываем только те пункты, у которых нет permission ИЛИ есть нужное право
const filteredItems = computed(() =>
    items.value.filter(item => item.permission === null || hasPermission(item.permission))
);

</script>

<template>
    <ProfileLayout>
        <template #left-center-column>
            <ProfileCard :company="company" />
        </template>

        <template #right-center-column>
            <InfoCard :company="company" title="Общая информация" />
            <ContextMenu global :model="filteredItems" class="mobile-area" />
        </template>
        <template #center-column v-if="company.branches?.length > 0">
            <BranchCard :branches="company.branches" title="Филиалы" />
        </template>
    </ProfileLayout>
</template>

<style scoped></style>
