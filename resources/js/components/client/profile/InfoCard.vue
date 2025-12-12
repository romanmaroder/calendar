<script setup lang="ts">
import { Client } from '@/types';
import { PropType } from 'vue';

defineProps({
    client: { type: Object as PropType<Client | null>},
    title: { type: String, default: '' },
});
</script>

<template>
    <Card class="rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <slot>
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-semibold">{{ title }}</h3>
                    <!--            <Button icon="pi pi-pencil" class="p-button-text" />-->
                </div>

                <div class="mt-3 space-y-4 text-sm text-slate-700 dark:text-slate-200">
                    <div v-if="client?.birthday" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Дата рождения:</div>
                        <time class="font-medium" :datetime="client?.birthday">{{ client?.birthday }}</time>
                    </div>

                    <div v-if="client?.branch" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Филиал:</div>
                        <div class="font-medium">{{ client?.branch?.name }}</div>
                    </div>

                    <div v-if="client?.created_at" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Дата регистрации:</div>
                        <time class="font-medium" :datetime="client?.created_at">{{ client?.created_at }}</time>
                    </div>

                    <div v-if="client?.comment" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Заметки:</div>
                        <div class="font-medium">{{ client?.comment }}</div>
                    </div>
                </div>
            </slot>
        </template>
    </Card>
</template>

<style scoped></style>
