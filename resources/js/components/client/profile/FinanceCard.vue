<script setup lang="ts">
defineProps({
    data: { type: Object, required: true },
    title: { type: String,default: '' },
});
</script>

<template><Card class="rounded-xl shadow-sm not-dark:!bg-gray-100">
    <template #content>
        <div class="flex items-start justify-between">
            <h3 class="text-lg font-semibold">{{title}}</h3>
        </div>
        <div class="mt-3 space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-300">Общая:</span>
                <span class="text-emerald-400">{{ data.total }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-300">Средний чек:</span>
                <span class="text-emerald-400">{{ data.aov }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-300">Кол-во записей:</span>
                <span class="text-emerald-400">{{ data.records}}</span>
            </div>
        </div>
    </template>
</Card>
</template>

<style scoped></style>