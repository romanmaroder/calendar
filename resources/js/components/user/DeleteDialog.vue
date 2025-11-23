<script setup lang="ts">
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import { getFullname } from '@/composables/useFullname';

const toast = useToast();
const visible = ref(false);
const wait = (time = 2000) => new Promise((resolve) => setTimeout(resolve, time));
const emit = defineEmits(['deleteItem']);
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
});

function handleAction() {
    axios
        .post(route(props.route, props.entity.id), { _method: 'delete' })
        .then((response) => {
            wait();
            toast.add({
                severity: 'info',
                summary: 'Info Message',
                detail: response.data.message,
                life: 3000,
            });
            emit('deleteItem', props.entity, response.data.message);
            visible.value = false;
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
    <Button icon="pi pi-user-minus" @click="visible = true" severity="danger" variant="text" size="small" />
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
        <template #header><span class="m-0 text-[17px] font-semibold dark:text-surface-400">Are you absolutely sure? </span></template>

        <span class="text-surface-500 dark:text-surface-400 mb-1 block font-semibold">
           {{entity.id}} - {{ getFullname({ name: entity.name, surname: entity.surname }) }}
        </span>
        <span class="text-red-500"><b>will be moved to the basket.</b></span>

        <div class="flex justify-end gap-2 mt-2">
            <Button type="button" size="small" label="Cancel" severity="secondary" @click="visible = false"></Button>
            <Button type="button" size="small" label="Yes, delete account" severity="danger" @click="handleAction" />
        </div>
    </Dialog>
</template>

<style scoped></style>