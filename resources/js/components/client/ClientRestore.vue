<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import Button from 'primevue/button';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';

const emit = defineEmits(['restoreCustomer']);
const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
    text: {
        type: String,
        default: 'Restore',
    },
    iconName: {
        type: String,
    },
    urlToRestore: {
        type: String,
        default: 'clients.restore',
    },
});

const toast = useToast();

const restore = (id: any) => {
    if (id !== null) {
        axios
            .post(route(props.urlToRestore, id), { _method: 'put' })
            .then((response) => {
                toast.add({
                    severity: 'info',
                    summary: 'Info Message',
                    detail: response.data.message,
                    life: 3000,
                });
                emit('restoreCustomer', id, response.data.message);
            })
            .catch(function (error) {
                toast.add({
                    severity: 'error',
                    summary: 'Error Message',
                    detail: error.message,
                    life: 3000,
                });
            });
    }

};
</script>

<template>
    <Button
        severity="secondary"
        @click.prevent="restore(props.id)"
        class="bg-transparent! border-none! outline-none! hover:shadow-sm! focus:shadow-[0_0_0_1px]! focus:shadow-emerald-500! dark:focus:shadow-green-800!"
    >
        <Icon v-if="iconName" :name="iconName" class="mr-1" />
        <span v-else>{{ text }}</span>
    </Button>
</template>
