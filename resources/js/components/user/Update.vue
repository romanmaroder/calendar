<script setup lang="ts">
import AvatarUpload from '@/components/AvatarUpload.vue';
import Icon from '@/components/Icon.vue';
import InputError from '@/components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import Drawer from 'primevue/drawer';
import FloatLabel from 'primevue/floatlabel';
import Inplace from 'primevue/inplace';
import InputMask from 'primevue/inputmask';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

const props = defineProps({
    entity: {
        type: Object,
        default() {
            return {};
        },
    },
    route: {
        type: String,
    },
    iconName: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: 'Edit',
    },
});

const emit = defineEmits(['UpdateItem']);

const wait = (time = 500) => new Promise((resolve) => setTimeout(resolve, time));
const open = ref(false);
const toast = useToast();
const visible = ref(false);

const nameInput = ref();
const phoneInput = ref<HTMLInputElement | null>(null);
const emailInput = ref<HTMLInputElement | null>(null);
const sourceInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    id: props.entity.id,
    avatar: props.entity.avatar,
    name: props.entity.name,
    surname: props.entity.surname,
    middleName: props.entity.middleName,
    phone: props.entity.phone,
    email: props.entity.email,
    source: props.entity.source,
    comment: props.entity.comment,
    discount: props.entity.discount,
    blacklist: props.entity.blacklist,
    prepayment: props.entity.prepayment,
    created_at: props.entity.created_at,
    total: props.entity.total,
    records: props.entity.records,
});

const submit = (e: Event) => {
    e.preventDefault();

    form.put(route(props.route, { user: form.data() }), {
        preserveScroll: true,
        onSuccess: function () {
            wait().then(() => (open.value = false));
            toast.add({
                severity: 'info',
                summary: 'Info',
                detail: form.name + ' - update successfully.',
                life: 3000,
            });
            closeModal();
            visible.value = false;
            emit('UpdateItem');
        },
        onError: function (errors) {
            toast.add({
                severity: 'error',
                summary: 'Validation Error',
                detail: showErrors(errors),
                life: 2000,
            });
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
    form.defaults();
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
        :header="form.surname"
        :blockScroll="true"
        position="right"
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
                <Inplace class="flex items-center justify-center" :pt="{ display: { class: '!inline-flex !flex-col !items-center' } }">
                    <template #display v-if="!form.avatar">
                        Add Avatar
                        <Icon name="Camera" class="h-[28px] w-[28px]" />
                    </template>
                    <template #display v-else>
                        Update Avatar
                        <Icon name="Camera" class="h-[28px] w-[28px]" />
                    </template>
                    <template #content="{ closeCallback }">
                        <div class="relative inline-flex max-h-[200px] max-w-[180px] items-center gap-2 rounded-[4px] bg-[#83BCE1] p-4 shadow-md">
                            <AvatarUpload
                                text-add="Добавить фото"
                                text-delete="Удалить фото"
                                :entity="entity"
                                updateUrl="avatar"
                                @updateAvatar="onUpdateAvatar"
                            />

                            <InputError :message="form.errors.avatar" class="my-2" />
                            <Button icon="pi pi-times" text severity="danger" @click="closeCallback" class="!absolute !top-[0px] !right-[0px]" />
                        </div>
                    </template>
                </Inplace>
                <div class="mt-2 space-y-4">
                    <FloatLabel variant="on" class="">
                        <InputText
                            id="name"
                            v-model="form.name"
                            autocomplete="off"
                            class="h-[28px] w-full"
                            aria-labelledby="name"
                            size="small"
                            ref="nameInput"
                        />
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
                            ref="emailInput"
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
                            ref="sourceInput"
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
                    <InputError :message="form.errors.comment" class="mt-2 mb-2" />
                </div>
                <div class="mt-2">
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
                        <Button severity="success" class="h-[30px] cursor-pointer" type="submit" @click="submit" raised>
                            <Icon name="Save" />
                            {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                        </Button>
                        <Button severity="secondary" @click="closeModal" class="h-[30px] cursor-pointer rounded-sm py-1.5 font-normal" raised>
                            Отмена
                        </Button>
                    </div>
                </div>
            </div>
        </form>
    </Drawer>
    <Button @click="visible = true" size="small" variant="link">
        <Icon :name="iconName" />
        {{ label }}
    </Button>
</template>
