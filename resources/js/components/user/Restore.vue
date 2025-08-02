<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';

const emit = defineEmits(['restoreCustomer']);
const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
    route: {
        type: String,
        default: 'clients.restore',
    },
    iconName: {
        type: String,
    },
    label: {
        type: String,
        default: 'Restore',
    },
});

const toast = useToast();

const restore = (id: any) => {
    if (id !== null) {
        axios
            .post(route(props.route, id), { _method: 'put' })
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
        variant="link"
        size="small"
        @click.prevent="restore(props.id)"
    >
        <Icon v-if="iconName" :name="iconName" />
        <span v-else>{{ label }}</span>
    </Button>
</template>
