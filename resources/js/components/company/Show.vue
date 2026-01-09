<script setup lang="ts">
import { useMediaQuery } from '@vueuse/core';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Company } from '@/types';
import { PropType, ref, watch } from 'vue';
import CompanyProfile from '@/components/company/profile/CompanyProfile.vue';

const props = defineProps({
    company: {
        type: Object as PropType<Company>,
        required: true,
    },
    route: {
        type: String,
    },
    iconName: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: 'Edit',
    },
    variant: {
        type: String,
        validator(value: string) {
            return ['outlined', 'text', 'link'].includes(value);
        },
        default: 'link',
    },
});

const visible = ref(false);

const isLargeScreen = useMediaQuery('(min-width: 640px)');

watch(isLargeScreen, () => {
    visible.value = false;
});
</script>

<template>
    <Drawer
        v-model:visible="visible"
        :header="props.company.name"
        :blockScroll="true"
        position="bottom"
        closeIcon="pi pi-chevron-down"
        :pt="{
            root: {
                class: 'sm:hidden! h-[100vh]! dark:bg-black!',
            },
            content: {
                class: 'p-0!',
            },
            mask: {
                class: 'sm:hidden!',
            },
            header: {
                class: '!py-[0.5rem] ![text-shadow:_0.5px_0.5px_2px_rgba(0,0,0,0.5)]',
            },
            title: {
                class: '!text-lg',
            },
        }"
    >
        <ProfileLayout>
            <template #center-column>
                <CompanyProfile :company="company" />
            </template>
        </ProfileLayout>
    </Drawer>
    <Button @click="visible = true" :icon="iconName" :label="label" size="small" variant="link" />
</template>
