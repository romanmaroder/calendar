<script setup lang="ts">
import AvatarUploader from '@/components/AvatarUploader.vue';
import InfoCard from '@/components/user/profile/InfoCard.vue';
import InputError from '@/components/InputError.vue';
import ProfileCard from '@/components/user/profile/ProfileCard.vue';
import Layout from '@/layouts/AppLayout.vue';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { BreadcrumbItem, User } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { useLabelName } from '@/composables/useLabelName';
import { useToast } from 'primevue/usetoast';
import { PropType } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Update user', href: '' },
];

const emit = defineEmits(['UpdateUser']);

const props = defineProps({
    user: Object as PropType<User>,
    branch: {
        type: Array,
    },
    default() {
        return {};
    },
});
const toast = useToast();

const form = useForm({
    id: props.user?.id,
    avatar: props.user?.avatar,
    name: props.user?.name,
    surname: props.user?.surname,
    middleName: props.user?.middleName,
    phone: props.user?.phone,
    email: props.user?.email,
    branch_id: props.user?.branch_id,
    birthday: props.user?.birthday,
    comment: props.user?.comment,
    created_at: props.user?.created_at,
});


const avatarUrl = (url: string) => {
    form.avatar = url;
    console.log(form.avatar);
};

const submit = (e: Event) => {
    e.preventDefault();
    if (form.id) {
        form.put(route('users.edit', { id: form.id }), {
            preserveScroll: true,
            onSuccess: function () {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' - update successfully.',
                    life: 3000,
                });
                emit('UpdateUser');
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

const setDate = (date: any): void => {
    form.birthday = dateLabelName(date);
};
</script>

<template>
    <Head title="Create user" />
    <Layout :breadcrumbs="breadcrumbs">
        <form>
            <ProfileLayout>
                <template #left-center-column>
                    <ProfileCard :user="null">
                        <div class="mb-2 space-y-4">
                            <AvatarUploader :avatar="form.avatar" @cropped="avatarUrl" @delete="(value) => console.log(value)" />
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
                                <InputError :message="form.errors.name" class="-mt-2 mb-2" />
                            </FloatLabel>
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
                                <InputError :message="form.errors.middleName" class="-mt-2 mb-2" />
                            </FloatLabel>
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
                                <InputError :message="form.errors.surname" class="-mt-2 mb-2" />
                            </FloatLabel>
                        </div>
                    </ProfileCard>
                </template>
                <template #right-center-column>
                    <div class="mb-2 space-y-4">
                        <InfoCard :user="null" title="Общая инфа">
                            <div class="space-y-4">
                                <FloatLabel variant="on" class="">
                                    <Select
                                        v-model="form.branch_id"
                                        id="branch"
                                        optionLabel="name"
                                        :options="props.branch"
                                        option-value="id"
                                        class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                        aria-labelledby="branch"
                                        size="small"
                                        fluid
                                    />
                                    <label for="branch" class="bg-transparent! font-light!">{{ 'Филиал' }}</label>
                                    <InputError :message="form.errors.branch_id" class="-mt-2 mb-2" />
                                </FloatLabel>
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
                                    <InputError :message="form.errors.phone" class="-mt-2 mb-2" />
                                </FloatLabel>

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
                                    <label class="bg-transparent! font-light!" for="birthday">{{ form.birthday || 'ДР' }}</label>
                                </FloatLabel>
                                <InputError :message="form.errors.birthday" class="-mt-2 mb-2" />
                            </div>
                        </InfoCard>
                    </div>
                </template>
                <template #center-column>
                    <ProfileCard :user="null">
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
                                <label class="font-light!" for="comment">Заметка</label>
                            </FloatLabel>
                            <InputError :message="form.errors.comment" class="mt-1 mb-2" />
                        </div>
                    </ProfileCard>
                </template>
                <template #right-column>
                    <ProfileCard :user="null">
                        <div class="flex flex-col items-stretch justify-center gap-2 sm:flex-row sm:justify-end md:justify-center">
                            <Button :disabled="form.processing" class="cursor-pointer" @click="submit" raised>
                                {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                            </Button>
                            <Button severity="secondary" @click="cancel" class="cursor-pointer" raised> Отмена</Button>
                        </div>
                    </ProfileCard>
                </template>
            </ProfileLayout>
        </form>
    </Layout>
</template>
