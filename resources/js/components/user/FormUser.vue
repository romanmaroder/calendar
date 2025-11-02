<script setup lang="ts">
import AvatarUpload from '@/components/AvatarUpload.vue';
import InputError from '@/components/InputError.vue';
import { getFullname } from '@/composables/useFullname';
import { useLabelName } from '@/composables/useLabelName';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { inject, ref, watch } from 'vue';

const branches: any = inject('listOfBranches');
const routes: any = inject('formRoutesProps');

const props = defineProps({
    user: {
        type: Object,
        default() {
            return {};
        },
    },
});

const emit = defineEmits(['createItem', 'previewOfUserData', 'updateItem']);
const wait = (time = 1000) => new Promise((resolve) => setTimeout(resolve, time));
const open = ref(false);
const toast = useToast();

const form = useForm({
    id: props.user.id ?? '',
    avatar: props.user.avatar ?? '',
    name: props.user.name ?? '',
    surname: props.user.surname ?? '',
    middleName: props.user.middleName ?? '',
    phone: props.user.phone ?? '',
    email: props.user.email ?? '',
    branch_id: props.user.branch_id ?? '',
    birthday: props.user.birthday ?? '',
    comment: props.user.comment ?? '',
    created_at: props.user.created_at ?? '',
    password: props.user.password ?? '',
});

const submit = (e: Event) => {
    e.preventDefault();
    if (form.id) {
        form.put(route(`${routes.value.update}`, { user: form.data() }), {
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
                emit('updateItem');
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
    } else {
        form.post(route(routes.value.create), {
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
                emit('createItem');
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
    }
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
};

const { dateLabelName, selectLabelName } = useLabelName();

const setDate = (date: any): void => {
    form.birthday = dateLabelName(date);
};

const onUpdateAvatar = (data: any) => {
    form.avatar = data.src;
    toast.add({ severity: 'info', summary: 'Info', detail: data.message, life: 3000 });
};

/*onMounted(()=>{
})*/

watch(form, () => {
    emit('previewOfUserData', { name: form.name, surname: form.surname, avatar: form.avatar });
});
</script>

<template>
    <form class="flex h-full flex-1 flex-col gap-4 rounded-xl sm:flex-row sm:p-4 dark:bg-[#18181B]">
        <div class="md:border-sidebar-border/70 dark:sm:border-sidebar-border relative hidden flex-none rounded-xl sm:block sm:w-56">
            <div v-if="$slots.avatarColumn">
                <slot name="avatarColumn" />
            </div>
            <div v-else>
                <AvatarUpload :url="routes?.upload" orientation="portrait" @updateAvatar="onUpdateAvatar" preview img-position="bottom" />
            </div>
        </div>
        <div class="grid flex-1 gap-4 sm:grid-cols-2 md:grid-flow-row-dense md:grid-cols-2 md:grid-rows-3">
            <div
                class="md:border-sidebar-border/70 dark:sm:border-sidebar-border relative grid content-start gap-3 rounded-xl p-2 sm:col-span-1 sm:row-span-1 sm:border md:col-span-1"
            >
                <FloatLabel variant="on" class="">
                    <InputText
                        id="name"
                        v-model="form.name"
                        autocomplete="off"
                        class="w-full"
                        aria-labelledby="name"
                        size="small"
                        v-keyfilter="/^[A-zА-яёЁ\s]+$/iu"
                    />
                    <label for="name" class="font-light!">{{ getFullname({ name: form.name }) || 'Имя:' }}</label>
                </FloatLabel>
                <InputError :message="form.errors.name" class="-mt-2 mb-2" />
                <FloatLabel variant="on" class="">
                    <InputText
                        id="middleName"
                        v-model="form.middleName"
                        autocomplete="off"
                        class="w-full"
                        aria-labelledby="middleName"
                        size="small"
                        v-keyfilter="/^[A-zА-яёЁ\s]+$/iu"
                    />
                    <label for="middleName" class="font-light!">{{ getFullname({ middlename: form.middleName }) || 'Отчество:' }}</label>
                </FloatLabel>
                <InputError :message="form.errors.middleName" class="-mt-2 mb-2" />
                <FloatLabel variant="on" class="">
                    <InputText
                        id="surname"
                        v-model="form.surname"
                        autocomplete="off"
                        class="w-full"
                        aria-labelledby="surname"
                        size="small"
                        v-keyfilter="/^[A-zА-яёЁ\s]+$/iu"
                    />
                    <label for="surname" class="font-light!">{{ getFullname({ surname: form.surname }) || 'Фамилия:' }}</label>
                </FloatLabel>
                <InputError :message="form.errors.surname" class="-mt-2 mb-2" />
                <FloatLabel variant="on" class="">
                    <Select
                        v-model="form.branch_id"
                        id="branch"
                        optionLabel="name"
                        :options="branches"
                        option-value="id"
                        class="w-full"
                        aria-labelledby="branch"
                        size="small"
                        fluid
                    />
                    <label for="branch" class="font-light!">{{ selectLabelName(branches, form.branch_id) || 'Филиал' }}</label>
                </FloatLabel>
                <InputError :message="form.errors.branch_id" class="-mt-2 mb-2" />
            </div>
            <div
                class="sm:border-sidebar-border/70 dark:sm:border-sidebar-border relative grid content-start gap-3 overflow-hidden rounded-xl p-2 sm:border md:col-span-1 md:row-span-1"
            >
                <FloatLabel variant="on">
                    <InputMask
                        id="phone"
                        v-model="form.phone"
                        type="tel"
                        class="w-full"
                        aria-labelledby="phone"
                        size="small"
                        mask="+9 999 999 99 99"
                    />
                    <label for="phone" class="font-light!">{{ form.phone || '+9 999 999 99 99' }}</label>
                </FloatLabel>
                <InputError :message="form.errors.phone" class="-mt-2 mb-2" />

                <FloatLabel variant="on" class="">
                    <InputText id="email" v-model="form.email" type="email" autocomplete="off" class="w-full" aria-labelledby="email" size="small" />
                    <label for="email" class="font-light!">Email:</label>
                </FloatLabel>
                <InputError :message="form.errors.email" class="-mt-2 mb-2" />
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
                        class="w-full"
                        aria-labelledby="birthday"
                        @update:model-value="setDate"
                    />
                    <label class="font-light!" for="birthday">{{ form.birthday || 'ДР' }}</label>
                </FloatLabel>
                <InputError :message="form.errors.birthday" class="-mt-2 mb-2" />
            </div>
            <div
                class="md:border-sidebar-border/70 dark:sm:border-sidebar-border relative grid content-start overflow-hidden rounded-xl p-2 sm:col-span-2 sm:border md:col-span-2 md:row-span-1"
            >
                <FloatLabel variant="on">
                    <Textarea id="comment" v-model="form.comment" rows="4" cols="15" autoResize size="small" class="w-full" />
                    <label class="font-light!" for="comment">Заметка</label>
                </FloatLabel>
                <InputError :message="form.errors.comment" class="mt-1 mb-2" />
                <FloatLabel variant="on" class="hidden">
                    <Password
                        id="password"
                        v-model="form.password"
                        size="small"
                        class="hidden"
                        toggleMask
                        :pt="{
                            root:'!w-full !hidden',
                            pcInputText: { root: { class: '!w-full !hidden' } }
                        }"
                    />
                    <label for="password" class="hidden">Password</label>
                </FloatLabel>
            </div>
            <div
                class="md:border-sidebar-border/70 dark:sm:border-sidebar-border relative grid content-start gap-2 overflow-hidden rounded-xl p-2 sm:col-span-2 sm:hidden md:col-span-2 md:row-span-1 md:border"
            >
                <div v-if="$slots.avatarRow">
                    <slot name="avatarRow" />
                </div>
                <div v-else>
                    <AvatarUpload :url="routes?.upload" orientation="landscape" @updateAvatar="onUpdateAvatar" img-position="left" preview />
                </div>
            </div>
            <div
                class="relative grid grid-flow-row content-evenly gap-2 overflow-hidden rounded-xl p-2 sm:col-span-2 sm:row-span-1 sm:grid-flow-col sm:self-start md:justify-self-end"
            >
                <Button :disabled="form.processing" class="cursor-pointer" @click="submit" raised>
                    {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                </Button>
                <Button severity="secondary" @click="closeModal" class="cursor-pointer" raised> Отмена</Button>
            </div>
        </div>
    </form>
</template>
