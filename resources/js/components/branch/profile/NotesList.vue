<script setup lang="ts">
defineProps({
    notes: { type: Array, required: true },
    title: { type: String,default: '' },
});
</script>

<template>
    <Card class="h-full rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-lg font-semibold">{{title}}</h3>
                <Button outlined severity="secondary" size="small" label="СКАЧАТЬ" />
            </div>

            <ul class="space-y-3 text-sm">
                <li v-for="(note, i) in notes" :key="i" class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-md bg-gray-50 dark:bg-gray-800">📝</div>
                        <div>
                            <div class="font-medium">{{ note.title }}</div>
                            <div class="text-xs text-slate-400 dark:text-slate-300">{{ note.size }}</div>
                        </div>
                    </div>
                    <button @click="$emit('download', note)" class="text-slate-400 dark:text-slate-300">⬇️</button>
                </li>
            </ul>
        </template>
    </Card>
</template>

<style scoped></style>