<script setup lang="ts">
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Company } from '@/types';
import { PropType, ref } from 'vue';
import InfoCard from './InfoCard.vue';
import ProfileCard from './ProfileCard.vue';
import CountryCard from '@/components/company/profile/CountryCard.vue';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
});
const items = ref([
    {
        label: 'Edit',
        icon: 'pi pi-pencil',
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
        command: () => {
            try {
                window.location.href = route('company.index');
            } catch (error) {
                console.error('Маршрут не найден:', error);
            }
        },
    },
]);
</script>

<template>
    <ProfileLayout>
        <template #left-center-column>
            <ProfileCard :company="company" />
        </template>

        <template #right-center-column>
            <InfoCard :company="company" title="Общая информация" />
            <ContextMenu global :model="items" class="mobile-area"/>
        </template>
        <template #right-column>
            <CountryCard :company="company" title="Country"/>
        </template>
    </ProfileLayout>
</template>

<style scoped></style>
