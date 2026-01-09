<script setup lang="ts">
import { getFullname } from '@/composables/useFullname';
import { useToast } from 'primevue/usetoast';
import { computed, PropType, ref } from 'vue';
import axios from 'axios';

const toast = useToast();
const visible = ref(false);
const wait = (time = 2000) => new Promise((resolve) => setTimeout(resolve, time));

const emit = defineEmits(['unscribeItem', 'unscribeItems']);

// Общий тип для любой сущности с id? name, surname
interface Identifiable {
    id: number | string;
    name?: string;
    surname?: string;
}

const props = defineProps({
    subscriber: {
        type: [Object, Array] as PropType<Identifiable | Array<Identifiable>>,
        required: true,
        default: () => {
            return {};
        },
    },
    channel:{
        type: Object,
        required:true,
        default:()=>({})
    },
    text: {
        type: String,
    },
    iconName: {
        type: String,
    },
    route: {
        type: String,
        default: '#',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String as PropType<'multi' | 'single'>,
        validator(value: string): boolean {
            return ['multi', 'single'].includes(value);
        },
        default: 'single',
    },
});

// Определяем, множественное ли удаление
const isMultiple = computed(() => Array.isArray(props.subscriber));

// Для множественного: считаем количество
const count = computed(() => (Array.isArray(props.subscriber) ? props.subscriber.length : 0));


function handleAction() {
    const data = isMultiple.value ? { ids: (props.subscriber as Identifiable[]).map((item: any) => item.id) } : {
        ids: [(props.subscriber as Identifiable).id ]};
    axios
        .post(route(props.route, { id: props.channel.id, ...data }), { id: props.channel.id, ...data  })
        .then((response) => {
            wait();
            toast.add({
                severity: 'info',
                summary: 'Info Message',
                detail: response.data.message,
                life: 3000,
            });

            // Эмиттим разное событие в зависимости от типа subscriber
            if (isMultiple.value) {
                emit('unscribeItems', props.subscriber, response.data.message);
            } else {
                emit('unscribeItem', props.subscriber, response.data.message);
            }

            visible.value = false;
        })
        .catch((error) => {
            wait();
            toast.add({
                severity: 'error',
                summary: 'Error Message',
                detail: error.message,
                life: 3000,
            });
        });
}
</script>

<template>
    <!-- Кнопка вызова диалога -->
    <Button
        v-if="type === 'multi'"
        :label="Array.isArray(props.subscriber) ? `Отписать (${count})` : '0'"
        :icon="iconName"
        severity="danger"
        raised
        :disabled="disabled"
        @click="visible = true"
        v-tooltip.top="'Отписать от филиала'"
    >
        <i :class="iconName"></i>
        {{text}}
        <!-- Показываем Badge только для множественного удаления -->
        <Badge severity="secondary">{{ count }}</Badge>
    </Button>
    <Button v-if="type === 'single'"
            :icon="iconName"
            @click="visible = true"
            severity="danger"
            variant="outlined"
            size="small"
            v-tooltip.top="'Отписать от филиала'"
            raised
    />

    <!-- Диалог подтверждения -->
    <Dialog v-model:visible="visible" modal :style="{ width: '25rem' }" :breakpoints="{ '768px': '50vw', '425px': '90vw' }">
        <template #header>
            <span class="dark:text-surface-400 m-0 text-[17px] font-semibold"> Are you absolutely sure? </span>
        </template>

        <!-- Список элементов для удаления (если множественное) -->
        <ol v-if="Array.isArray(subscriber)">
            <li v-for="item in subscriber" :key="item.id" class="text-surface-500 dark:text-surface-400 mb-1 block font-semibold">
                {{ item.id }} - {{ getFullname({ name: item.name, surname: item.surname }) }}
            </li>
        </ol>

        <!-- Одиночный элемент -->
        <div v-else class="text-surface-500 dark:text-surface-400 mb-1 block font-semibold">
            {{ subscriber.id }} - {{ getFullname({ name: subscriber.name, surname: subscriber.surname }) }}
        </div>

        <span class="text-red-500"><b>will be detached from the branch.</b></span>

        <div class="mt-2 flex justify-end gap-2">
            <Button type="button" size="small" label="Cancel" severity="secondary" raised @click="visible = false" />
            <Button type="button" size="small" label="Yes, unsubscribe" severity="danger" raised @click="handleAction" />
        </div>
    </Dialog>
</template>
