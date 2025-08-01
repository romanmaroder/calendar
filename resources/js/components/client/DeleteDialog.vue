<script setup lang="ts">
import {
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogOverlay,
    AlertDialogPortal,
    AlertDialogRoot,
    AlertDialogTitle,
    AlertDialogTrigger,
} from 'reka-ui';
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
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
        default: 'clients.destroy',
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
                emit('deleteItem', props.entity.id, response.data.message);
            })
            .catch(function(error) {
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
    <AlertDialogRoot>
        <AlertDialogTrigger
            class="inline-flex h-[35px] cursor-pointer items-center justify-center rounded-md px-[15px] text-sm leading-none font-semibold transition-all outline-none hover:shadow-sm focus:shadow-[0_0_0_1px] focus:shadow-red-500 dark:focus:shadow-red-800"
        >
            <Icon v-if="iconName" :name="iconName" class="mr-1 text-red-600 hover:text-red-900 focus:text-red-900" />
            <span class="text-stone-400 dark:text-stone-700" v-else>{{ text }}</span>
        </AlertDialogTrigger>
        <AlertDialogPortal>
            <AlertDialogOverlay class="dark:bg-black-900 data-[state=open]:animate-overlayShow fixed inset-0 z-30 bg-zinc-900/70" />
            <AlertDialogContent
                class="data-[state=open]:animate-contentShow fixed top-[50%] left-[50%] z-[100] max-h-[85vh] w-[90vw] max-w-[500px] translate-x-[-50%] translate-y-[-50%] rounded-lg bg-white p-[25px] text-sm shadow-[hsl(206_22%_7%_/_35%)_0px_10px_38px_-10px,_hsl(206_22%_7%_/_20%)_0px_10px_20px_-15px] focus:outline-none"
            >
                <AlertDialogTitle class="m-0 text-[17px] font-semibold text-black"> Are you absolutely sure? </AlertDialogTitle>
                <AlertDialogDescription class="mt-4 mb-5 text-sm leading-normal text-black">
                    {{ entity.name }} will be moved to the basket.
                </AlertDialogDescription>
                <div class="flex justify-end gap-4">
                    <AlertDialogCancel
                        class="inline-flex h-[35px] cursor-pointer items-center justify-center rounded-md bg-stone-500 px-[15px] leading-none font-semibold text-white transition-all outline-none hover:bg-stone-400 focus:shadow-[0_0_0_2px] focus:shadow-stone-700"
                    >
                        Cancel
                    </AlertDialogCancel>
                    <AlertDialogAction
                        class="inline-flex h-[35px] cursor-pointer items-center justify-center rounded-md bg-red-500 px-[15px] leading-none font-semibold text-red-100 outline-none hover:bg-red-400 focus:shadow-[0_0_0_2px] focus:shadow-red-700"
                        @click="handleAction"
                    >
                        Yes, delete account
                    </AlertDialogAction>
                </div>
            </AlertDialogContent>
        </AlertDialogPortal>
    </AlertDialogRoot>
</template>

<style scoped></style>