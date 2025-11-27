<script setup lang="ts">
import { useToast } from 'primevue/usetoast';
import { computed, PropType } from 'vue';
import axios from 'axios';

const toast = useToast();
const wait = (time = 2000) => new Promise((resolve) => setTimeout(resolve, time));

const emit = defineEmits(['restoreItem', 'restoreItems']);

// Общий тип для любой сущности с id
interface Identifiable {
    id: number | string;
}

const props = defineProps({
    entity: {
        type: [Object, Array] as PropType<Identifiable | Array<Identifiable>>,
        required: true,
        default: () => {
            return {};
        },
    },
    label: {
        type: String,
        default: 'Restore',
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

function restore() {
    const data = isMultiple.value ? { ids: (props.entity as Identifiable[]).map((item: any) => item.id) } : { id: (props.entity as Identifiable).id };
    axios
        .post(route(props.route, { ...data }), { ...data })
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
                emit('restoreItems', props.entity, response.data.message);
            } else {
                emit('restoreItem', props.entity, response.data.message);
            }
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
    <Button
        v-if="type === 'multi'"
        :label="Array.isArray(props.entity) ? `(${count})` : '0'"
        :icon="iconName"
        severity="success"
        raised
        :disabled="disabled"
        @click.prevent="restore()"
    >
        <i :class="iconName"></i>
        <!-- Показываем Badge только для множественного удаления -->
        <Badge severity="secondary">{{ count }}</Badge>
    </Button>
    <Button v-if="type === 'single'"
            :icon="iconName"
            severity="success"
            variant="text"
            size="small"
            @click.prevent="restore()" />
</template>
