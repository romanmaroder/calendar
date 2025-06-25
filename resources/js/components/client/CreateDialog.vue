<script setup lang="ts">
import AvatarUpload from '@/components/AvatarUpload.vue';
import Icon from '@/components/Icon.vue';
import InputError from '@/components/InputError.vue';
import {
    Dialog,
    DialogClose,
    DialogContentWithoutBtnClose,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';

import Checkbox from 'primevue/checkbox';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { ref } from 'vue';

const props = defineProps({
    urlToUpdate: {
        type: String,
        default: 'clients.store',
    },
    iconName: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: 'Add',
    },
    title: {
        type: String,
        default: 'New',
    },
});

const wait = (time = 1000) => new Promise((resolve) => setTimeout(resolve, time));
const open = ref(false);
const toast = useToast();

const form = useForm({
    id: '',
    avatar: '',
    name: '',
    surname: '',
    middleName: '',
    phone: '',
    email: '',
    source: '',
    comment: '',
    discount: '',
    blacklist: false,
    prepayment: false,
    created_at: '',
    total: '',
    records: '',
});

const emit = defineEmits(['addItem']);

const submit = (e: Event) => {
    e.preventDefault();
    form.post(route(props.urlToUpdate), {
        preserveScroll: true,
        onSuccess: function () {
            wait().then(() => (open.value = false));
            emit('addItem', form.data());
            toast.add({
                severity: 'info',
                summary: 'Info',
                detail: form.name + ' ' + form.surname + ' - client add successfully.',
                life: 3000,
            });
            closeModal();
        },
        onFinish: function () {
            showErrors(form.errors);
        },
    });
};

const showErrors = (errors) => {
    if (errors.name) {
        toast.add({
            severity: 'error',
            summary: 'Error Message',
            detail: errors.name,
            life: 3000,
        });
    }
    if (errors.phone) {
        toast.add({
            severity: 'error',
            summary: 'Error Message',
            detail: errors.phone,
            life: 3000,
        });
    }
    if (errors.email) {
        toast.add({
            severity: 'error',
            summary: 'Error Message',
            detail: errors.email,
            life: 3000,
        });
    }
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
const close = () => {
    closeModal();
};

const onUpdateAvatar = (data: any) => {
    form.avatar = data.url;
    toast.add({ severity: 'info', summary: 'Info', detail: data.message, life: 3000 });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" size="small" raised>
                <Icon :name="iconName" />
                {{ label }}
            </Button>
        </DialogTrigger>
        <DialogContentWithoutBtnClose class="rounded-none lg:min-w-[640px] dark:bg-stone-50 dark:text-black">
            <DialogTitle className="sr-only">{{ label }}</DialogTitle>
            <DialogDescription aria-describedby="update client"></DialogDescription>
            <DialogHeader @close="close" class="h-[47px] bg-[#1b2133] px-4 text-white dark:bg-[#1b2133]/80">
                {{ title }}
            </DialogHeader>
            <form class="p-3.5 dark:bg-black">
                <div class="parent grid md:grid-cols-[repeat(3,_1fr)] md:grid-rows-[repeat(5,_auto)] md:gap-x-[10px] md:gap-y-[10px]">
                    <div class="mx-auto mb-2 [grid-area:1_/_1_/_2_/_2] md:mb-0">
                        <div class="max-h-[200px] max-w-[180px] rounded-[4px] bg-[#83BCE1] p-4 shadow-md">
                            <AvatarUpload text-add="Добавить фото" text-delete="Удалить фото" updateUrl="avatar" @updateAvatar="onUpdateAvatar" />
                        </div>

                        <InputError :message="form.errors.avatar" class="my-2" />
                    </div>
                    <div class="mt-2 space-y-4 md:[grid-area:1_/_2_/_2_/_3] lg:space-y-5">
                        <FloatLabel variant="on" class="">
                            <InputText
                                id="in_label"
                                v-model="form.name"
                                autocomplete="off"
                                class="h-[28px] w-full"
                                aria-labelledby="name"
                                size="small"
                            />
                            <label for="on_label" class="font-light!">Имя:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.name" class="mb-2" />

                        <FloatLabel variant="on" class="">
                            <InputText
                                id="in_label"
                                v-model="form.surname"
                                autocomplete="off"
                                class="h-[28px] w-full"
                                aria-labelledby="surname"
                                size="small"
                            />
                            <label for="on_label" class="font-light!">Фамилия:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.surname" class="mb-2" />

                        <FloatLabel variant="on" class="">
                            <InputText
                                id="in_label"
                                v-model="form.middleName"
                                autocomplete="off"
                                class="h-[28px] w-full"
                                aria-labelledby="middleName"
                                size="small"
                            />
                            <label for="on_label" class="font-light!">Отчество:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.middleName" class="mb-2" />
                    </div>
                    <div class="mt-2 space-y-4 md:[grid-area:1_/_3_/_2_/_4] lg:space-y-5">
                        <FloatLabel variant="on" class="">
                            <InputText
                                id="in_label"
                                v-model="form.phone"
                                type="tel"
                                autocomplete="off"
                                class="h-[28px] w-full"
                                aria-labelledby="phone"
                                size="small"
                            />
                            <label for="on_label" class="font-light!">Телефон:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.phone" class="mb-2" />

                        <FloatLabel variant="on" class="">
                            <InputText
                                id="in_label"
                                v-model="form.email"
                                type="email"
                                autocomplete="off"
                                class="h-[28px] w-full"
                                aria-labelledby="email"
                                size="small"
                            />
                            <label for="on_label" class="font-light!">Email:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.email" class="mb-2" />

                        <FloatLabel variant="on" class="">
                            <InputText
                                id="in_label"
                                v-model="form.source"
                                type="text"
                                autocomplete="off"
                                class="h-[28px] w-full"
                                aria-labelledby="source"
                                size="small"
                            />
                            <label for="on_label" class="font-light!">Источник:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.source" class="mb-2" />
                    </div>
                    <div class="mt-2 md:[grid-area:2_/_1_/_3_/_4]">
                        <FloatLabel variant="on">
                            <Textarea id="on_label" v-model="form.comment" rows="4" cols="15" autoResize size="small" class="w-full" />
                            <label for="on_label">Заметка</label>
                        </FloatLabel>
                        <InputError :message="form.errors.comment" class="mb-2" />
                    </div>
                    <div class="mb-2 md:[grid-area:3_/_1_/_4_/_4]">
                        <FloatLabel variant="on" class="">
                            <InputText
                                id="in_label"
                                v-model="form.discount"
                                type="text"
                                autocomplete="off"
                                class="h-[28px] w-full"
                                aria-labelledby="discount"
                                size="small"
                            />
                            <label for="on_label" class="font-light!">Персональная скидка:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.discount" class="mb-2" />
                    </div>
                    <div class="grid gap-y-1.5 md:[grid-area:4_/_1_/_5_/_4]">
                        <div class="card flex flex-col flex-wrap gap-4 dark:text-white">
                            <div class="flex items-center gap-2">
                                <Checkbox v-model="form.blacklist" inputId="blackList" name="blackList" size="small" :tabindex="4" binary />
                                <label for="blackList"> В черном списке </label>
                            </div>
                            <div class="flex items-center gap-2">
                                <Checkbox v-model="form.prepayment" inputId="alwaysPrepayment" name="prepayment" size="small" :tabindex="4" binary />
                                <label for="alwaysPrepayment">Всегда по предоплате</label>
                            </div>
                        </div>
                    </div>
                    <div class="md:[grid-area:5_/_2_/_6_/_4]">
                        <div class="mt-2 flex flex-col justify-end gap-2 sm:flex-row md:mt-0">
                            <Button severity="success" :disabled="form.processing" class="h-[30px] cursor-pointer" @click="submit" raised>
                                <Icon name="Save" />
                                {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                            </Button>
                            <DialogClose as-child>
                                <Button severity="secondary" @click="close"
                                        class="h-[30px] cursor-pointer rounded-sm py-1.5 font-normal" raised>
                                    Отмена
                                </Button>
                            </DialogClose>
                        </div>
                    </div>
                </div>
            </form>
        </DialogContentWithoutBtnClose>
    </Dialog>
</template>
