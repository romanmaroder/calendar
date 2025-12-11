<script setup lang="ts">
defineProps({
    data: { type: Object, required: true },
    title: { type: String,default: '' },
});
</script>

<template>
    <Card class="rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-semibold">{{title}}</h3>
                </div>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-300">За текущий месяц:</span>
                        <span class="text-emerald-400">{{
                            data.month
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-300">За текущий день:</span>
                        <span class="text-emerald-400">{{ data.currentDay
                        }}</span>
                    </div>
                </div>
        </template>
    </Card>
</template>

<style scoped></style>