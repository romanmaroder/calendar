<script setup lang="ts">
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { onUpdated, ref } from 'vue';
import { getFullname } from '@/composables/useFullname';

const toast = useToast();
const wait = (time = 2000) => new Promise((resolve) => setTimeout(resolve, time));
const emit = defineEmits(['deleteItems']);
const count = ref(0);
const visible = ref(false);
const props = defineProps({
    entity: {
        type: Object,
        required: true,
        default() {
            return {};
        },
    },
    text: {
        type: String,
        default: 'Delete ',
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
    },
});

onUpdated(() => {
    if (props.entity == null) {
        count.value = 0;
    } else {
        count.value = props.entity.length;
    }
});

function handleAction() {
    axios
        .post(route(props.route, { ids: props.entity.map((val: any) => val.id) }), { _method: 'delete' })
        .then((response) => {
            wait();
            toast.add({
                severity: 'info',
                summary: 'Info Message',
                detail: response.data.message,
                life: 3000,
            });
            emit('deleteItems', props.entity, response.data.message);
        })
        .catch(function (error) {
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
    <Button label="Корзина" icon="pi pi-trash" severity="danger" raised :disabled="disabled" @click="visible = true">
        <i class="pi pi-trash"></i>
        <Badge severity="secondary">{{ count }}</Badge>
    </Button>
    <Dialog
        v-model:visible="visible"
        modal
        :style="{
            width: '25rem',
        }"
        :breakpoints="{
            '768px': '50vw',
            '425px': '90vw',
        }"
    >
        <template #header><span class="dark:text-surface-400 m-0 text-[17px] font-semibold">Are you absolutely sure? </span></template>
        <ol>
            <li v-for="item in entity" :key="item.id" class="text-surface-500 dark:text-surface-400 mb-1 block font-semibold">
                {{ item.id }} - {{ getFullname({ name: item.name, surname: item.surname }) }}
            </li>
        </ol>
        <span class="text-red-500"><b> will be moved to the basket.</b></span>

        <div class="mt-2 flex justify-end gap-2">
            <Button type="button" size="small" label="Cancel" severity="secondary" @click="visible = false"></Button>
            <Button type="button" size="small" label="Yes, delete account" severity="danger" @click="handleAction" />
        </div>
    </Dialog>
</template>
