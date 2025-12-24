<script setup lang="ts">
import { getFullname } from '@/composables/useFullname';
import { useToast } from 'primevue/usetoast';
import { computed, PropType, ref } from 'vue';
import axios from 'axios';

const toast = useToast();
const visible = ref(false);
const wait = (time = 2000) => new Promise((resolve) => setTimeout(resolve, time));

const emit = defineEmits(['deleteItem', 'deleteItems']);

// Общий тип для любой сущности с id? name, surname
interface Identifiable {
    id: number | string;
    name?: string;
    surname?: string;
    deleted_at?: Date;
}

const props = defineProps({
    entity: {
        type: [Object, Array] as PropType<Identifiable | Array<Identifiable>>,
        required: true,
        default: () => {
            return {};
        },
    },
    text: {
        type: String,
        default: '',
    },
    deleteLabelBtn:{
        type: String,
        default: 'Yes, delete account',
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
const isMultiple = computed(() => Array.isArray(props.entity));

// Для множественного: считаем количество
const count = computed(() => (Array.isArray(props.entity) ? props.entity.length : 0));

// Проверка на softDelete и forceDelete
const isDeleted = computed(() => {
    return isMultiple.value
        ? (props.entity as Identifiable[]).every((item: any) => item.deleted_at === null)
        : (props.entity as Identifiable).deleted_at === null;
});

function handleAction() {
    const data = isMultiple.value ? { ids: (props.entity as Identifiable[]).map((item: any) => item.id) } : { id: (props.entity as Identifiable).id };
    axios
        .post(route(props.route, { ...data }), { ...data, _method: 'delete' })
        .then((response) => {
            wait();
            toast.add({
                severity: 'info',
                summary: 'Info Message',
                detail: response.data.message,
                life: 3000,
            });

            // Эмиттим разное событие в зависимости от типа entity
            if (isMultiple.value) {
                emit('deleteItems', props.entity, response.data.message);
            } else {
                emit('deleteItem', props.entity, response.data.message);
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
        :label="Array.isArray(props.entity) ? `Корзина (${count})` : '0'"
        :icon="iconName"
        severity="danger"
        raised
        :disabled="disabled"
        @click="visible = true"
    >
        <i :class="iconName"></i>
        <!-- Показываем Badge только для множественного удаления -->
        <Badge severity="secondary">{{ count }}</Badge>
    </Button>
    <Button v-if="type === 'single'" :icon="iconName" @click="visible = true" severity="danger" variant="text" size="small" />

    <!-- Диалог подтверждения -->
    <Dialog v-model:visible="visible" modal :style="{ width: '25rem' }" :breakpoints="{ '768px': '50vw', '425px': '90vw' }">
        <template #header>
            <span class="dark:text-surface-400 m-0 text-[17px] font-semibold"> Are you absolutely sure? </span>
        </template>

        <!-- Список элементов для удаления (если множественное) -->
        <ol v-if="Array.isArray(entity)">
            <li v-for="item in entity" :key="item.id" class="text-surface-500 dark:text-surface-400 mb-1 block font-semibold">

                {{ item.id }} - <span v-if="item.surname">{{ getFullname({ name: item.name, surname:  item.surname })  }}</span>
                {{item.name}}
            </li>
        </ol>

        <!-- Одиночный элемент -->
        <div v-else class="text-surface-500 dark:text-surface-400 mb-1 block font-semibold">
            {{ entity.id }} - <span v-if="entity.surname">{{ getFullname({ name: entity.name, surname: entity.surname
        }) }}</span>  {{entity.name}}
        </div>

        <div v-if="isDeleted" class="text-red-500">
            <span v-if="text">{{text}} </span>
            <span v-else>will be moved to the basket. </span>
        </div>
        <span v-else class="text-red-500"><b>will be deleted forever.</b></span>

        <div class="mt-2 flex justify-end gap-2">
            <Button type="button" size="small" label="Cancel" severity="secondary" raised @click="visible = false" />
            <Button type="button" size="small" :label="deleteLabelBtn" severity="danger" raised
                    @click="handleAction" />
        </div>
    </Dialog>
</template>
