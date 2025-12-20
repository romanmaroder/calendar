<script setup lang="ts">
import { PropType } from 'vue';
import { Branch } from '@/types';
import { useStatus } from '@/composables/useStatus';

const props = defineProps({
    branch: { type: Object as PropType<Branch | null> },
    title: { type: String, default: '' },
});

const { label, severity } = useStatus(props.branch?.status);

</script>

<template>
    <Card class="rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <div class="flex items-start justify-between mb-3">
                <h3 class="text-sm md:text-lg font-semibold text-shadow-slate-500 dark:text-slate-300">{{ title }}</h3>
                <!--            <Button icon="pi pi-pencil" class="p-button-text" />-->
            </div>
            <slot>
                <div class="mt-3 space-y-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Контакты:</div>
                        <div class="font-medium">{{ branch?.contact }}</div>
                    </div>
                    <div v-if="branch?.status" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Статус:</div>
                        <div class="font-medium">
                            <Tag :value="label" :severity="severity" />
                        </div>
                    </div>
                    <div v-if="branch?.description" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Заметки:</div>
                        <div class="font-medium">{{ branch?.description }}</div>
                    </div>
                </div>
            </slot>
        </template>
    </Card>
</template>

<style scoped></style>