<script setup lang="ts">
import { computed, PropType } from 'vue';
import { User } from '@/types';

const props =defineProps({
    user: { type: Object as PropType<User | null>},
    title: { type: String, default: '' },
});

const roleNames = computed(() => {
    if (!props.user?.roles || !Array.isArray(props.user.roles)) return '';
    return props.user.roles.map((r: { name: string }) => r.name).join(',  ');
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
                    <div v-if="user?.birthday" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Дата рождения:</div>
                        <time class="font-medium" :datetime="user?.birthday">{{ user?.birthday }}</time>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Филиал:</div>
                        <div class="font-medium">{{ user?.branch?.name }}</div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Дата регистрации:</div>
                        <time class="font-medium" :datetime="user?.created_at">{{ user?.created_at }}</time>
                    </div>
                    <div v-if="user?.roles.length >0" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Роль:</div>
                        <div class="font-medium text-emerald-400">{{ roleNames }}</div>
                    </div>

                    <div v-if="user?.comment" class="flex flex-wrap">
                        <div class="w-44 text-slate-500 dark:text-slate-300">Заметки:</div>
                        <div class="font-medium">{{ user?.comment }}</div>
                    </div>
                </div>
            </slot>
        </template>
    </Card>
</template>

<style scoped></style>