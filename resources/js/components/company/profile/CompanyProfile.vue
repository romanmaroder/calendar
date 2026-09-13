<script setup lang="ts">
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Company } from '@/types';
import { computed, inject, PropType, ref } from 'vue';
import InfoCard from './InfoCard.vue';
import ProfileCard from './ProfileCard.vue';
import BranchCard from '@/components/company/profile/BranchCard.vue';
import { router, usePage } from '@inertiajs/vue3';
import { CompanyTranslations } from '@/types/translations';
import { route } from 'ziggy-js';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
});

const translations = inject<CompanyTranslations>('translations');

const page = usePage();

const hasPermission = (permission: string) => {
    const userPermissions = page.props.auth?.user?.permissions ?? [];
    return userPermissions.includes(permission);
};

const items = ref([
    {
        label: translations?.button.edit,
        icon: 'pi pi-pencil',
        permission: 'companies.edit',
        command: () => {
            try {
                router.visit(route('company.edit', props.company.id));
            } catch (error) {
                console.error(translations?.toast?.route_not_found, error);
            }
        },
    },
    {
        label: translations?.companies,
        icon: 'pi pi-building',
        permission: null,
        command: () => {
            try {
                router.visit(route('company.index'));
            } catch (error) {
                console.error(translations?.toast?.route_not_found, error);
            }
        },
    },
]);

// Фильтруем: показываем только те пункты, у которых нет permission ИЛИ есть нужное право
const filteredItems = computed(() => items.value.filter((item) => item.permission === null || hasPermission(item.permission)));
</script>

<template>
    <ProfileLayout>
        <template #left-center-column>
            <ProfileCard :company="company" />
        </template>

        <template #right-center-column>
            <InfoCard :company="company" title="" />
            <ContextMenu global :model="filteredItems" class="mobile-area" />
        </template>
        <template #center-column v-if="company.branches?.length > 0">
            <BranchCard :branches="company.branches" :title="translations?.label.branches" />
        </template>
    </ProfileLayout>
</template>

<style scoped></style>
