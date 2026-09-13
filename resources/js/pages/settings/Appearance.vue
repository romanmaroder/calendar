<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { provide, ref } from 'vue';
import { AppearanceTranslations } from '@/types/translations';

const props = defineProps<{
    translations: AppearanceTranslations;
}>();

const layout = ref(props.translations.layout);
provide('layout', layout);

const tabs = ref(props.translations.tabs);
provide('tabs', tabs);

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: props.translations.appearance_settings,
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="translations.appearance_settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall :title="translations.appearance_settings" :description="translations.appearance_description" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
