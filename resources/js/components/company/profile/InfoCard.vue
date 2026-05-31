<script setup lang="ts">
import { PropType } from 'vue';
import { Company } from '@/types';
import { useStatus } from '@/composables/useStatus';

const props = defineProps({
    company: { type: Object as PropType<Company | null> },
    title: { type: String, default: '' },
});

const { severity } = useStatus(props.company?.status);
</script>

<template>
    <Card class="rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <div class="mb-3 flex items-start justify-between">
                <h3 class="text-sm font-semibold text-shadow-slate-500 md:text-lg dark:text-slate-300">{{ title }}</h3>
                <!--            <Button icon="pi pi-pencil" class="p-button-text" />-->
            </div>
            <slot>
                <div class="mt-3 space-y-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="flex flex-wrap justify-between">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Контакты:</div>
                        <div class="font-medium">{{ company?.contact }}</div>
                    </div>
                    <div v-if="company?.description" class="flex flex-wrap justify-between">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Описание:</div>
                        <div class="font-medium">{{ company?.description }}</div>
                    </div>
                    <div v-if="company?.info" class="flex flex-wrap justify-between">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Инфо:</div>
                        <div class="font-medium">{{ company?.info }}</div>
                    </div>
                    <div v-if="company?.branches_count" class="flex flex-wrap justify-between">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Филиалы:</div>
                        <div class="font-medium">
                            <Tag :value="company?.branches_count" :severity="severity"/>
                        </div>
                    </div>
                </div>
            </slot>
        </template>
    </Card>
</template>

<style scoped></style>