<script setup lang="ts">
import axios from 'axios';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import Badge from 'primevue/badge';
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
import { onUpdated, ref } from 'vue';

const toast = useToast();
const wait = (time = 2000) => new Promise((resolve) => setTimeout(resolve, time));
const emit = defineEmits(['deleteItems']);
const count = ref(0);

const props = defineProps({
    entity: {
        type: Object,
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
    urlToDelete: {
        type: String,
        default: 'clients.destroy',
    },
    disabled: {
        type: Boolean,
    },
});

onUpdated(()=>{
    if (props.entity == null) {
        count.value = 0;
    } else {
        count.value = props.entity.length;
    }
})

function handleAction() {
    axios
        .post(route(props.urlToDelete, { ids: props.entity.map((val: any) => val.id) }), { _method: 'delete' })
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
    <AlertDialogRoot>
        <AlertDialogTrigger as-child>
            <Button label="Корзина"
                    icon="pi pi-trash"
                    severity="danger"
                    raised
                    :disabled="disabled" >
                <i class="pi pi-trash"></i>
                <Badge severity="secondary">{{count}}</Badge>
            </Button>
        </AlertDialogTrigger>
        <AlertDialogPortal>
            <AlertDialogOverlay class="dark:bg-black-900 data-[state=open]:animate-overlayShow fixed inset-0 z-30 bg-zinc-900/70" />
            <AlertDialogContent
                class="data-[state=open]:animate-contentShow fixed top-[50%] left-[50%] z-[100] max-h-[85vh] w-[90vw] max-w-[500px] translate-x-[-50%] translate-y-[-50%] rounded-lg bg-white p-[25px] text-sm shadow-[hsl(206_22%_7%_/_35%)_0px_10px_38px_-10px,_hsl(206_22%_7%_/_20%)_0px_10px_20px_-15px] focus:outline-none"
            >
                <AlertDialogTitle class="m-0 text-[17px] font-semibold text-black"> Are you absolutely sure? </AlertDialogTitle>
                <AlertDialogDescription class="mt-4 mb-5 text-sm leading-normal text-black">
                    <span v-for="item in entity" :key="item.id"> {{ item.name }} {{item.surname}}<br /> </span>
                    <span class="text-red-500"><b> will be moved to the basket.</b></span>
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
                        Yes, delete
                    </AlertDialogAction>
                </div>
            </AlertDialogContent>
        </AlertDialogPortal>
    </AlertDialogRoot>
</template>

<style scoped></style>
