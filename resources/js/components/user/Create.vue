<script setup lang="ts">
import AvatarUpload from '@/components/AvatarUpload.vue';
import Icon from '@/components/Icon.vue';
import InputError from '@/components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

const props = defineProps({
    route: {
        type: String,
        default: '#',
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
const emit = defineEmits(['CreateItem']);
const wait = (time = 1000) => new Promise((resolve) => setTimeout(resolve, time));
const open = ref(false);
const visible = ref(false);

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

const submit = (e: Event) => {
    e.preventDefault();
    form.post(route(props.route), {
        preserveScroll: true,
        onSuccess: function () {
            wait().then(() => (open.value = false));
            toast.add({
                severity: 'info',
                summary: 'Info',
                detail: form.name + ' ' + form.surname + ' - add successfully.',
                life: 3000,
            });
            closeModal();
            visible.value = false;
            emit('CreateItem');
        },
        onFinish: function () {
            form.reset();
        },
        onError: function (errors) {
            toast.add({
                severity: 'error',
                summary: 'Validation Error',
                detail: showErrors(errors),
                life: 2000,
            });
            form.defaults();
        },
    });
};

const showErrors = (errors: any) => {
    let message = '';
    for (const key in errors) {
        message += `${errors[key]}` + '\n';
    }
    return message;
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
    visible.value = false;
};

const onUpdateAvatar = (data: any) => {
    form.avatar = data.url;
    toast.add({ severity: 'info', summary: 'Info', detail: data.message, life: 3000 });
};
</script>

<template>
    <Drawer
        v-model:visible="visible"
        :header="title"
        :blockScroll="true"
        :pt="{
            header: {
                class: '!py-[0.5rem]',
            },
            title: {
                class: '!text-lg',
            },
        }"
    >
        <form class="p-3.5 dark:bg-black">
            <div class="grid gap-5">
                <Inplace class="flex justify-center" :pt="{display:{class:'!inline-flex !flex-col !items-center'}}">
                    <template #display> Add Avatar
                        <Icon name="Camera" class="w-[28px] h-[28px]" />
                    </template>
                    <template #content="{ closeCallback }">
                        <div class="inline-flex items-center gap-2 relative max-h-[200px] max-w-[180px] rounded-[4px] bg-[#83BCE1] p-4 shadow-md">
                                    <AvatarUpload
                                        text-add="Добавить фото"
                                        text-delete="Удалить фото"
                                        updateUrl="avatar"
                                        @updateAvatar="onUpdateAvatar"
                                    />
                                <InputError :message="form.errors.avatar" class="my-2" />
                            <Button icon="pi pi-times" text severity="danger" @click="closeCallback"
                                    class="!absolute !top-[0px] !right-[0px]" />
                        </div>
                    </template>
                </Inplace>

                <div class="mt-2 space-y-4">
                    <FloatLabel variant="on" class="">
                        <InputText id="name" v-model="form.name" autocomplete="off"
                                   class="h-[28px] w-full"
                                   aria-labelledby="name" size="small" />
                        <label for="name" class="font-light!">{{ form.name || 'Имя:' }}</label>
                    </FloatLabel>
                    <InputError :message="form.errors.name" class="-mt-2 mb-2" />
                    <FloatLabel variant="on" class="">
                        <InputText
                            id="middleName"
                            v-model="form.middleName"
                            autocomplete="off"
                            class="h-[28px] w-full"
                            aria-labelledby="middleName"
                            size="small"
                        />
                        <label for="middleName" class="font-light!">{{ form.middleName || 'Отчество:' }}</label>
                    </FloatLabel>
                    <InputError :message="form.errors.middleName" class="-mt-2 mb-2" />
                    <FloatLabel variant="on" class="">
                        <InputText
                            id="surname"
                            v-model="form.surname"
                            autocomplete="off"
                            class="h-[28px] w-full"
                            aria-labelledby="surname"
                            size="small"
                        />
                        <label for="surname" class="font-light!">{{ form.surname || 'Фамилия:' }}</label>
                    </FloatLabel>
                    <InputError :message="form.errors.surname" class="-mt-2 mb-2" />
                </div>
                <div class="mt-2 space-y-4">
                    <FloatLabel variant="on">
                        <InputMask
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            class="h-[28px] w-full"
                            aria-labelledby="phone"
                            size="small"
                            ref="phoneInput"
                            mask="+9 999 999 99 99"
                        />
                        <label for="phone" class="font-light!">{{ form.phone || '+9 999 999 99 99' }}</label>
                    </FloatLabel>
                    <InputError :message="form.errors.phone" class="-mt-2 mb-2" />

                    <FloatLabel variant="on" class="">
                        <InputText
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="off"
                            class="h-[28px] w-full"
                            aria-labelledby="email"
                            size="small"
                            required
                        />
                        <label for="email" class="font-light!">Email:</label>
                    </FloatLabel>
                    <InputError :message="form.errors.email" class="-mt-2 mb-2" />

                    <FloatLabel variant="on" class="">
                        <InputText
                            id="source"
                            v-model="form.source"
                            type="text"
                            autocomplete="off"
                            class="h-[28px] w-full"
                            aria-labelledby="source"
                            size="small"
                        />
                        <label for="source" class="font-light!">Источник:</label>
                    </FloatLabel>
                    <InputError :message="form.errors.source" class="-mt-2 mb-2" />
                </div>
                <div class="mt-2">
                    <FloatLabel variant="on">
                        <Textarea id="comment" v-model="form.comment" rows="4" cols="15" autoResize size="small" class="w-full" />
                        <label for="comment">Заметка</label>
                    </FloatLabel>
                    <InputError :message="form.errors.comment" class="mt-1 mb-2" />
                </div>
                <div class="mt-2 mb-2">
                    <FloatLabel variant="on" class="">
                        <InputText
                            id="discount"
                            v-model="form.discount"
                            type="text"
                            autocomplete="off"
                            class="h-[28px] w-full"
                            aria-labelledby="discount"
                            size="small"
                        />
                        <label for="discount" class="font-light!">Персональная скидка:</label>
                    </FloatLabel>
                    <InputError :message="form.errors.discount" class="mt-1 mb-2" />
                </div>
                <div class="grid gap-y-1.5">
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
                <div class="">
                    <div class="mt-2 flex flex-col justify-end gap-2 sm:flex-row md:mt-0">
                        <Button severity="success" :disabled="form.processing" class="h-[30px] cursor-pointer" @click="submit" raised>
                            <Icon name="Save" />
                            {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                        </Button>
                        <Button severity="secondary" @click="closeModal"
                                class="h-[30px] cursor-pointer rounded-sm py-1.5 font-normal" raised>
                            Отмена
                        </Button>
                    </div>
                </div>
            </div>
        </form>
    </Drawer>
    <Button @click="visible = true" size="small">
        <Icon :name="iconName" />
        {{ label }}
    </Button>
</template>
