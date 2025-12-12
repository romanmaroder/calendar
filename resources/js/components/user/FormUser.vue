<!--Contains a form for creating/editing a user.
Notifies the user about creation/editing and returns the data for display in the preview.-->
<script setup lang="ts">
import AvatarUploader from '@/components/AvatarUploader.vue';
import InputError from '@/components/InputError.vue';
import InfoCard from '@/components/user/profile/InfoCard.vue';
import ProfileCard from '@/components/user/profile/ProfileCard.vue';
import { useLabelName } from '@/composables/useLabelName';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { User } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { inject, PropType, watch } from 'vue';

const emit = defineEmits(['createUser', 'updateUser', 'drawerData']);

const props = defineProps({
    user: Object as PropType<User | null>,
});

const toast = useToast();

const form = useForm({
    id: props.user?.id ?? '',
    avatar: props.user?.avatar ?? '',
    name: props.user?.name ?? '',
    surname: props.user?.surname ?? '',
    middleName: props.user?.middleName ?? '',
    phone: props.user?.phone ?? '',
    email: props.user?.email ?? '',
    branch_id: props.user?.branch_id ?? '',
    birthday: props.user?.birthday ?? '',
    comment: props.user?.comment ?? '',
    created_at: props.user?.created_at ?? '',
});

const branches: any = inject('listOfBranches');

watch(form, () => {
    emit('drawerData', { name: form.name, surname: form.surname, avatar: form.avatar });
});
const onUpdateCropped = (value: string) => {
    form.avatar = value;
    console.log('FORM ' + form.avatar, 'URL ' + value);
};
const submit = () => {
    if (form.id) {
        form.put(route('users.update', { id: form.id }), {
            preserveScroll: true,
            onSuccess: function () {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' - update successfully.',
                    life: 3000,
                });
                emit('updateUser');
            },
            onError: function (errors) {
                toast.add({
                    severity: 'error',
                    summary: 'Validation Error' + errors,
                    life: 2000,
                });
            },
        });
    } else {
        form.post(route('users.store'), {
            preserveScroll: true,
            onSuccess: function () {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' ' + form.surname + ' - add successfully.',
                    life: 3000,
                });
                emit('createUser');
            },
            onFinish: function () {
                //form.reset();
            },
            onError: function (errors) {
                toast.add({
                    severity: 'error',
                    summary: 'Validation Error' + errors,
                    //detail: showErrors(errors),
                    life: 2000,
                });
                form.defaults();
            },
        });
    }
};
const onDeleteAvatar = () => {
    if (form.id) {
        form.put(route('users.avatar', { id: form.id }), {
            preserveScroll: true,
            onSuccess: function () {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' - update successfully.',
                    life: 3000,
                });
                form.avatar = '';
            },
            onError: function (errors) {
                toast.add({
                    severity: 'error',
                    summary: 'Validation Error' + errors,
                    life: 2000,
                });
            },
        });
    }
};
const cancel = () => {
    form.clearErrors();
    form.reset();
};

const { dateLabelName } = useLabelName();
const setDate = (date: Date | null): void => {
    form.birthday = dateLabelName(date);
};
</script>

<template>
    <form>
        <ProfileLayout>
            <template #left-center-column>
                <ProfileCard>
                    <div class="mb-2 space-y-4">
                        <AvatarUploader :avatar="form.avatar" @cropped="onUpdateCropped" @delete="onDeleteAvatar" />
                        <FloatLabel variant="on" class="">
                            <InputText
                                id="name"
                                v-model="form.name"
                                autocomplete="off"
                                class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                aria-labelledby="name"
                                size="small"
                                v-keyfilter="/^[A-zА-яёЁ\s]+$/iu"
                            />
                            <label for="name" class="bg-transparent! font-light!">{{ 'Имя:' }}</label>
                        </FloatLabel>
                        <InputError :message="form.errors.name" />
                        <FloatLabel variant="on" class="">
                            <InputText
                                id="middleName"
                                v-model="form.middleName"
                                autocomplete="off"
                                class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                aria-labelledby="middleName"
                                size="small"
                                v-keyfilter="/^[A-zА-яёЁ\s]+$/iu"
                            />
                            <label for="middleName" class="bg-transparent! font-light!">{{ 'Отчество:' }}</label>
                        </FloatLabel>
                        <InputError :message="form.errors.middleName" />
                        <FloatLabel variant="on" class="">
                            <InputText
                                id="surname"
                                v-model="form.surname"
                                autocomplete="off"
                                class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                aria-labelledby="surname"
                                size="small"
                                v-keyfilter="/^[A-zА-яёЁ\s]+$/iu"
                            />
                            <label for="surname" class="bg-transparent! font-light!">{{ 'Фамилия:' }}</label>
                        </FloatLabel>
                        <InputError :message="form.errors.surname" />
                    </div>
                </ProfileCard>
            </template>
            <template #right-center-column>
                <div class="mb-2 space-y-4">
                    <InfoCard title="Общая инфа">
                        <div class="space-y-4">
                            <FloatLabel variant="on" class="">
                                <Select
                                    v-model="form.branch_id"
                                    id="branch"
                                    optionLabel="name"
                                    :options="branches"
                                    option-value="id"
                                    class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                    aria-labelledby="branch"
                                    size="small"
                                    fluid
                                />
                                <label for="branch" class="bg-transparent! font-light!">{{ 'Филиал' }}</label>
                            </FloatLabel>
                            <InputError :message="form.errors.branch_id" />
                            <FloatLabel variant="on">
                                <InputMask
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                    aria-labelledby="phone"
                                    size="small"
                                    mask="+9 999 999 99 99"
                                    :aria-autocomplete="form.phone"
                                />
                                <label for="phone" class="bg-transparent! font-light!">{{ '+9 999 999 99 99' }}</label>
                            </FloatLabel>
                            <InputError :message="form.errors.phone" />

                            <FloatLabel variant="on" class="">
                                <InputText
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="off"
                                    class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                    aria-labelledby="email"
                                    size="small"
                                />
                                <label for="email" class="bg-transparent! font-light!">Email:</label>
                            </FloatLabel>
                            <InputError :message="form.errors.email" />
                            <FloatLabel variant="on">
                                <DatePicker
                                    id="birthday"
                                    v-model="form.birthday"
                                    dateFormat="yy-mm-dd"
                                    name="birthday"
                                    showIcon
                                    showButtonBar
                                    iconDisplay="input"
                                    size="small"
                                    aria-labelledby="birthday"
                                    @update:model-value="setDate"
                                    :pt="{
                                        root: '!w-full',
                                        pcInputText: {
                                            root: {
                                                class: '!w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none',
                                                // класс для корневого элемента
                                                style: { width: '100%' },
                                            },
                                            input: {
                                                class: 'inner-input', // класс для <input>
                                                style: { fontWeight: '500' },
                                            },
                                        },
                                    }"
                                />
                                <label class="bg-transparent! font-light!" for="birthday1">{{ 'ДР' }}</label>
                            </FloatLabel>
                            <InputError :message="form.errors.birthday" />
                        </div>
                    </InfoCard>
                </div>
            </template>
            <template #center-column>
                <ProfileCard>
                    <div class="mb-2 space-y-4">
                        <FloatLabel variant="on">
                            <Textarea
                                id="comment"
                                v-model="form.comment"
                                rows="4"
                                cols="15"
                                autoResize
                                size="small"
                                class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                            />
                            <label class="bg-transparent! font-light!" for="comment">Заметка</label>
                        </FloatLabel>
                        <InputError :message="form.errors.comment" />
                    </div>
                </ProfileCard>
            </template>
            <template #right-column>
                <ProfileCard>
                    <div class="flex flex-col items-stretch justify-center gap-2 sm:flex-row sm:justify-end md:justify-center">
                        <Button :disabled="form.processing" class="cursor-pointer" @click.prevent="submit" raised>
                            {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                        </Button>
                        <Button severity="secondary" @click.prevent="cancel" class="cursor-pointer" raised> Отмена </Button>
                    </div>
                </ProfileCard>
            </template>
        </ProfileLayout>
    </form>
</template>
