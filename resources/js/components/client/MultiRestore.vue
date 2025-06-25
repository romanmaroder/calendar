<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import { onMounted, onUpdated, ref } from 'vue';

const toast = useToast();
const emit = defineEmits(['restoreItems']);
const count = ref(0);

const props = defineProps({
    entity: {
        type: Object,
        default() {
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
    urlToRestore: {
        type: String,
        default: 'multiRestore',
    },
});

onMounted(() => {
    //console.log(props.entity);
});

onUpdated(() => {
    if (props.entity == null) {
        count.value = 0;
    } else {
        count.value = props.entity.length;
    }
});

const restore = (ids: any) => {
    axios
        .post(route(props.urlToRestore, { ids: ids.map((val: any) => val.id) }), { _method: 'put' })
        .then((response) => {
            toast.add({
                severity: 'info',
                summary: 'Info',
                detail: response.data.message,
                life: 3000,
            });
            emit('restoreItems', props.entity, response.data.message);
        })
        .catch(function (error) {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: error.message,
                life: 3000,
            });
        });
};
</script>

<template>
    <Button severity="primary" @click.prevent="restore(props.entity)" raised >
        <Icon v-if="iconName" :name="iconName" class="mr-1" />
        <span v-else>{{ label }}</span>
        <Badge severity="secondary">{{ count }}</Badge>
    </Button>
</template>
