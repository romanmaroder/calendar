<!--Contains a form for creating/editing a user.
Notifies the user about creation/editing and returns the data for display in the preview.-->
<script setup lang="ts">
import AvatarUploader from '@/components/AvatarUploader.vue';
import InputError from '@/components/InputError.vue';
import InfoCard from '@/components/user/profile/InfoCard.vue';
import ProfileCard from '@/components/user/profile/ProfileCard.vue';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Client } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { PropType, watch } from 'vue';
import { useDateField } from '@/composables/utils/useDateField';
import { usePhoneMeta } from '@/composables/utils/phone/usePhoneMeta';

const emit = defineEmits(['createUser', 'updateUser', 'drawerData']);

const props = defineProps({
    client: Object as PropType<Client | null>,
});

const toast = useToast();

const birthdayField = useDateField({
    initialValue: props.client?.birthday ?? null,
    format: 'local', // именно local для birthday
});

const form = useForm({
    id: props.client?.id ?? '',
    avatar: props.client?.avatar ?? '',
    name: props.client?.name ?? '',
    surname: props.client?.surname ?? '',
    middleName: props.client?.middleName ?? '',
    phone: props.client?.phone ?? '',
    email: props.client?.email ?? '',
    birthday: birthdayField.formValue.value,
    comment: props.client?.comment ?? '',
    blacklist: props.client?.blacklist ?? '',
    prepayment: props.client?.prepayment ?? '',
    discount: props.client?.discount,
    total: props.client?.total,
    records: props.client?.records,
    source: props.client?.source ?? '',
    created_at: props.client?.created_at ?? '',
});

watch(form, () => {
    emit('drawerData', { name: form.name, surname: form.surname, avatar: form.avatar });
});
const onUpdateCropped = (value: string) => {
    form.avatar = value;
    console.log('FORM ' + form.avatar, 'URL ' + value);
};
const submit = () => {
    // Перед отправкой убеждаемся, что в форме актуальное значение
    form.birthday = birthdayField.getPayloadValue();

    if (form.id) {
        form.put(route('clients.update', { id: form.id }), {
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
                console.error(errors);
                /*toast.add({
                    severity: 'error',
                    summary: 'Validation Error' + errors,
                    life: 2000,
                });*/
            },
        });
    } else {
        form.post(route('clients.store'), {
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
                console.error(errors);
                /*toast.add({
                    severity: 'error',
                    summary: 'Validation Error' + errors,
                    //detail: showErrors(errors),
                    life: 2000,
                });*/
                form.defaults();
            },
        });
    }
};
const onDeleteAvatar = () => {
    if (form.id) {
        form.put(route('clients.avatar', { id: form.id }), {
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
    window.history.back();
};

const { meta, load } = usePhoneMeta('/clients/form-meta');
load();
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
                                pattern="/^[A-Za-zА-Яа-яЁё\d\s.,\-]+$/"
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
                                pattern="/^[A-Za-zА-Яа-яЁё\d\s.,\-]+$/"
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
                                pattern="/^[A-Za-zА-Яа-яЁё\d\s.,\-]+$/"
                            />
                            <label for="surname" class="bg-transparent! font-light!">{{ 'Фамилия:' }}</label>
                        </FloatLabel>
                        <InputError :message="form.errors.surname" />
                    </div>
                </ProfileCard>
            </template>
            <template #right-center-column>
                <div class="mb-2 space-y-4">
                    <InfoCard title="Общая информация">
                        <div class="space-y-4">
                            <FloatLabel variant="on">
                                <InputMask
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                    aria-labelledby="phone"
                                    size="small"
                                    :mask="meta?.phone_mask"
                                    :aria-autocomplete="form.phone"
                                />
                                <label for="phone" class="bg-transparent! font-light!">{{ meta?.phone_mask ?? 'Телефон' }}</label>
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
                                    v-model="birthdayField.internalDate.value"
                                    dateFormat="yy-mm-dd"
                                    showIcon
                                    showButtonBar
                                    iconDisplay="input"
                                    size="small"
                                    aria-labelledby="birthday"
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
                            <FloatLabel variant="on" class="">
                                <InputText
                                    id="source"
                                    v-model="form.source"
                                    type="text"
                                    autocomplete="off"
                                    class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                    aria-labelledby="source"
                                    size="small"
                                />
                                <label for="source" class="bg-transparent! font-light!">Источник:</label>
                            </FloatLabel>
                            <InputError :message="form.errors.source" />
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
                                rows="1"
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
                    <div class="mb-2 space-y-4">
                        <FloatLabel variant="on">
                            <InputNumber
                                v-model="form.discount"
                                inputId="percent"
                                :min="0"
                                :max="100"
                                suffix="%"
                                size="small"
                                aria-labelledby="discount"
                                :pt="{
                                    root: 'w-full!',
                                    pcInputText: {
                                        root: 'w-full! rounded-none! border-0! border-b-1! bg-transparent! shadow-none!',
                                    },
                                }"
                            />
                            <label class="bg-transparent! font-light!" for="discount">Персональная скидка:</label>
                        </FloatLabel>
                        <InputError :message="form.errors.discount" />
                    </div>
                </ProfileCard>
                <ProfileCard>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <Checkbox v-model="form.blacklist" inputId="blackList" name="blackList" size="small" :tabindex="4" binary />
                            <label for="blackList"> В черном списке </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <Checkbox v-model="form.prepayment" inputId="alwaysPrepayment" name="prepayment" size="small" :tabindex="4" binary />
                            <label for="alwaysPrepayment">Всегда по предоплате</label>
                        </div>
                    </div>
                </ProfileCard>
                <ProfileCard>
                    <div class="flex flex-col items-stretch justify-center gap-2 sm:flex-row sm:justify-end md:justify-center">
                        <Button size="small" :disabled="form.processing" class="cursor-pointer" @click.prevent="submit"
                                raised>
                            {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                        </Button>
                        <Button size="small" severity="secondary" @click.prevent="cancel" class="cursor-pointer"
                                raised>
                            Отмена
                        </Button>
                    </div>
                </ProfileCard>
            </template>
        </ProfileLayout>
    </form>
</template>
