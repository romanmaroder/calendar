<script setup lang="ts">
import AvatarUploader from '@/components/AvatarUploader.vue';
import InfoCard from '@/components/user/profile/InfoCard.vue';
import InputError from '@/components/InputError.vue';
import ProfileCard from '@/components/user/profile/ProfileCard.vue';
import { selectLabelName } from '@/composables/useSelectLabelName';
import Layout from '@/layouts/AppLayout.vue';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, provide, readonly, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Create user', href: '' },
];

const props = defineProps({
    routing: Object,
    branch: Object,
    default() {
        return {};
    },
});

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
    password: '',
});

onMounted(() => {
    // console.log(props.routing);
});

const submit = (e: Event) => {
    e.preventDefault();
    if (form.id) {
        form.put(route(`${'props.routing.value.user.uri.update'}`, { id: form.id }), {
            preserveScroll: true,
            onSuccess: function () {
                /*toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' - update successfully.',
                    life: 3000,
                });*/
            },
            onError: function (errors) {
                /*toast.add({
                    severity: 'error',
                    summary: 'Validation Error',
                    detail: showErrors(errors),
                    life: 2000,
                });*/
            },
        });
    } else {
        form.post(route('props.routing.value.user.uri.store'), {
            preserveScroll: true,
            onSuccess: function () {
                /*toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' ' + form.surname + ' - add successfully.',
                    life: 3000,
                });*/
            },
            onFinish: function () {
                //form.reset();
            },
            onError: function (errors) {
                /*toast.add({
                    severity: 'error',
                    summary: 'Validation Error',
                    detail: showErrors(errors),
                    life: 2000,
                });*/
                form.defaults();
            },
        });
    }
};
const cansel = () => {
    form.clearErrors();
    form.reset();
};
const listOfBranches: object = ref(props.branch);
provide('listOfBranches', listOfBranches);

const formRoutesProps: object = ref(props.routing);
provide('formRoutesProps', readonly(formRoutesProps));
</script>

<template>
    <Head title="Create user" />
    <Layout :breadcrumbs="breadcrumbs">
        <form>
            <ProfileLayout>
                <template #left-center-column>
                    <ProfileCard :user="null">
                        <div class="mb-2 space-y-4">
                            <AvatarUploader :avatar="form.avatar" @cropped="(value)=>console.log(value)"
                                            @delete="(value)=>console.log(value)" />
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
                                <label for="name" class="font-light! bg-transparent!">{{ 'Имя:' }}</label>
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
                                <label for="middleName" class="font-light! bg-transparent!">{{ 'Отчество:' }}</label>
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
                                <label for="surname" class="font-light! bg-transparent!">{{ 'Фамилия:' }}</label>
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
                                        :options="[]"
                                        option-value="id"
                                        class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                        aria-labelledby="branch"
                                        size="small"
                                        fluid
                                    />
                                    <label for="branch" class="font-light! bg-transparent!">{{
                                        selectLabelName(
                                            [
                                                {
                                                    id: 1,
                                                    name: '',
                                                },
                                            ],
                                            form.branch_id,
                                        ) || 'Филиал'
                                    }}</label>
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
                                    <label for="phone" class="font-light! bg-transparent!">{{ '+9 999 999 99 99' }}</label>
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
                                    <label for="email" class="font-light! bg-transparent!">Email:</label>
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
                                        @update:model-value="new Date()"
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
                                    <label class="font-light! bg-transparent!" for="birthday">{{ 'ДР' }}</label>
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
                                <label class="font-light! bg-transparent!" for="comment">Заметка</label>
                            </FloatLabel>
                            <InputError :message="form.errors.comment" class="mt-1 mb-2" />
                        </div>
                    </ProfileCard>
                </template>
                <template #right-column>
                    <ProfileCard :user="null">
                        <div
                            class="flex flex-col items-stretch justify-center gap-2 sm:flex-row sm:justify-end md:justify-center"
                        >
                            <Button :disabled="form.processing" class="cursor-pointer" @click="submit" raised>
                                {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                            </Button>
                            <Button severity="secondary" @click="cansel" class="cursor-pointer" raised> Отмена</Button>
                        </div>
                    </ProfileCard>
                </template>
            </ProfileLayout>
        </form>
    </Layout>
</template>
