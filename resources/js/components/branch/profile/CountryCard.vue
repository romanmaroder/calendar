<script setup lang="ts">
import { PropType } from 'vue';
import { Branch } from '@/types';

defineProps({
    branch: { type: Object as PropType<Branch | null> },
    title: { type: String, default: '' },
});
</script>

<template>
    <Card class="rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <div class="flex items-start justify-between">
                <h3 class="text-lg font-semibold">{{ branch?.country?.name }}</h3>
            </div>
            <div class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-300">Code:</span>
                    <span class="font-medium">{{ branch?.country?.code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-300">ISO Code:</span>
                    <span class="font-medium">{{ branch?.country?.iso_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-300">Currency:</span>
                    <span class="font-medium">{{ branch?.country?.currency }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-300">Phone Code:</span>
                    <span class="font-medium">{{ branch?.country?.phone_code }}</span>
                </div>
            </div>
        </template>
    </Card>
</template>

<style scoped></style>