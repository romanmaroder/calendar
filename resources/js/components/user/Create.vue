<script setup lang="ts">
import AvatarUpload from '@/components/AvatarUpload.vue';
import Icon from '@/components/Icon.vue';
import InputError from '@/components/InputError.vue';
import { useLabelName } from '@/composables/useLabelName';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { inject, ref } from 'vue';
import { getFullname } from '@/composables/useFullname';

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

const branches: any = inject('listOfBranches');

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
    branch_id: '',
    birthday: '',
    comment: '',
    created_at: '',
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

const { dateLabelName, selectLabelName } = useLabelName();

const setDate = (date: any): void => {
    form.birthday = dateLabelName(date);
};

const onUpdateAvatar = (data: any) => {
    console.log(data);
    form.avatar = data.url;
    toast.add({ severity: 'info', summary: 'Info', detail: data.message, life: 3000 });
};

const resize = () => {
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 640) {
            visible.value = false;
        }
    });
};
resize();
</script>

<template>
    <Drawer
        v-model:visible="visible"
        :before-hide="form.reset()"
        :blockScroll="true"
        closeIcon="pi pi-chevron-left "
        :pt="{
            header: {
                class: '!py-[0.5rem]',
            },
            title: {
                class: '!text-lg',

            },
        }"
    >
        <template #header>
            <div class="flex items-center gap-2">
                <Avatar v-if="form.avatar" :image=form.avatar shape="circle" :pt="{
                    image:{
                        class: 'object-cover'
                    }
                }"/>
                <Avatar v-else  icon="pi pi-user" shape="circle" />
                <span v-if="form.name || form.surname" class="font-bold inline-block w-[150px] truncate">
                    {{ getFullname({name: form.name,surname: form.surname}) }}
                </span>
                <span v-else class="font-bold inline-block w-[150px] line-clamp-1">{{ title}}</span>
            </div>
        </template>
        <form class="p-3.5 dark:bg-black">
            <div class="grid gap-5">

<!--              <Inplace class="flex justify-center" :pt="{ display: { class: '!inline-flex !flex-col !items-center' } }">
                    <template #display>
                        Add Avatar
                         <Avatar  icon="pi pi-camera" shape="circle" />
                    </template>
                    <template #content="{ closeCallback }">
                        <div class="">
                            <InputError :message="form.errors.avatar" class="my-2" />
                            <Button icon="pi pi-times" text severity="danger" @click="closeCallback" class="!absolute !top-[0px] !right-[0px]" />
                        </div>
                    </template>
                </Inplace>-->

                <div class="mt-2 space-y-4">
                    <FloatLabel variant="on" class="">
                        <InputText id="name" v-model="form.name" autocomplete="off" class="h-[28px] w-full" aria-labelledby="name" size="small" />
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
                        />
                        <label for="email" class="font-light!">Email:</label>
                    </FloatLabel>
                    <InputError :message="form.errors.email" class="-mt-2 mb-2" />

                    <FloatLabel variant="on" class="">
                        <Select
                            v-model="form.branch_id"
                            id="branch"
                            optionLabel="name"
                            :options="branches"
                            option-value="id"
                            class="h-[28px] w-full"
                            aria-labelledby="branch"
                            size="small"
                            fluid
                        />
                        <label for="branch" class="font-light!">{{ selectLabelName(branches, form.branch_id) || 'Филиал' }}</label>
                    </FloatLabel>
                    <InputError :message="form.errors.branch_id" class="-mt-2 mb-2" />
                    <FloatLabel variant="on">
                        <DatePicker
                            :v-model="form.birthday"
                            dateFormat="yy-mm-dd"
                            id="birthday"
                            name="birthday"
                            showIcon
                            showButtonBar
                            iconDisplay="input"
                            size="small"
                            class="h-[28px] w-full"
                            aria-labelledby="birthday"
                            @update:model-value="setDate"
                        />
                        <label class="font-light!" for="birthday">{{ form.birthday || 'ДР' }}</label>
                    </FloatLabel>
                    <InputError :message="form.errors.birthday" class="-mt-2 mb-2" />
                </div>
                <div class="mt-2">
                    <FloatLabel variant="on">
                        <Textarea id="comment" v-model="form.comment" rows="4" cols="15" autoResize size="small" class="w-full" />
                        <label class="font-light!" for="comment">Заметка</label>
                    </FloatLabel>
                    <InputError :message="form.errors.comment" class="mt-1 mb-2" />
                </div>
                <div class="mt-2">
                    <AvatarUpload text-add="Добавить фото" text-delete="Удалить фото" updateUrl="avatar" @updateAvatar="onUpdateAvatar" />
                </div>
                <div class="">
                    <div class="mt-2 flex flex-col justify-end gap-2 sm:flex-row md:mt-0">
                        <Button :disabled="form.processing" class="h-[30px] cursor-pointer" @click="submit" raised>
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
    <Button @click="visible = true" size="small">
        <Icon :name="iconName" />
        {{ label }}
    </Button>
</template>
