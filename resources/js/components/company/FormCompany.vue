<!--Contains a form for creating/editing a company.
Notifies the company about creation/editing and returns the data for display in the preview.-->
<script setup lang="ts">
import AvatarUploader from '@/components/AvatarUploader.vue';
import InputError from '@/components/InputError.vue';
import InfoCard from '@/components/company/profile/InfoCard.vue';
import ProfileCard from '@/components/company/profile/ProfileCard.vue';
import { useCountryPhone } from '@/composables/utils/phone/useCountryPhone';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Company, Country } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { inject, PropType, Ref, watch } from 'vue';

const emit = defineEmits(['createCompany', 'updateCompany', 'drawerData']);
const countries: Ref<Country[]> | undefined = inject('countries');

const props = defineProps({
    company: Object as PropType<Company | null>,
});

const toast = useToast();

const form = useForm({
    id: props.company?.id ?? '',
    name: props.company?.name ?? '',
    phone: props.company?.phone ?? '',
    description: props.company?.description ?? '',
    contact: props.company?.contact ?? '',
    info: props.company?.info ?? '',
    avatar: props.company?.avatar ?? '',
    country_id: props.company?.country_id ?? null,
});

watch(form, () => {
        emit('drawerData', { name: form.name, avatar: form.avatar });
});

const { country, mask } = useCountryPhone({
    countries,
    form,
});

watch(
    () => country.value,
    (newCountry) => {
        if (newCountry !== null) {
            form.reset('phone');
        }
    },
);

const onUpdateCropped = (value: string) => {
    form.avatar = value;
    console.log('FORM ' + form.avatar, 'URL ' + value);
};
const submit = () => {
    if (form.id) {
        form.put(route('company.update', { id: form.id }), {
            preserveScroll: true,
            onSuccess: function () {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' - update successfully.',
                    life: 3000,
                });
                emit('updateCompany');
            },
            onError: function (errors) {
                console.log(errors);
                /*toast.add({
                    severity: 'error',
                    summary: 'Validation Error',
                    life: 2000,
                });*/
            },
        });
    } else {
        form.post(route('company.store'), {
            preserveScroll: true,
            onSuccess: function () {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' - add successfully.',
                    life: 3000,
                });
                emit('createCompany');
                form.reset();
            },
            onFinish: function () {
                //form.reset();
            },
            onError: function (errors) {
                console.log(errors);
                /*toast.add({
                    severity: 'error',
                    summary: 'Validation Error' + form.errors.name,
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
        form.put(route('company.avatar', { id: form.id }), {
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
                console.log(errors);
                toast.add({
                    severity: 'error',
                    summary: 'Validation Error',
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
                            <label for="name" class="bg-transparent! font-light!">{{ 'Название:' }}</label>
                        </FloatLabel>
                        <InputError :message="form.errors.name" />
                    </div>
                </ProfileCard>
            </template>
            <template #right-center-column>
                <div class="mb-2 space-y-4">
                    <InfoCard title="Общая информация">
                        <div class="flex flex-col flex-wrap space-y-4">
                            <div class="">
                                <FloatLabel variant="on" class="">
                                    <InputText
                                        id="contact"
                                        v-model="form.contact"
                                        autocomplete="off"
                                        class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                        aria-labelledby="contact"
                                        size="small"
                                        pattern="/^[A-Za-zА-Яа-яЁё\d\s.,\-]+$/"
                                    />
                                    <label for="contact" class="bg-transparent! font-light!">{{ 'Контакты:' }}</label>
                                </FloatLabel>
                                <InputError :message="form.errors.contact" />
                            </div>
                            <div class="">
                                <FloatLabel variant="on">
                                    <Textarea
                                        id="description"
                                        v-model="form.info"
                                        rows="1"
                                        cols="15"
                                        autoResize
                                        size="small"
                                        class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                    />
                                    <label class="bg-transparent! font-light!" for="description">Инфо</label>
                                </FloatLabel>
                                <InputError :message="form.errors.info" />
                            </div>
                            <div>
                                <FloatLabel variant="on" class="">
                                    <Select
                                        v-model="form.country_id"
                                        inputId="on_label"
                                        optionLabel="name"
                                        option-value="id"
                                        :options="countries"
                                        class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                        aria-labelledby="country"
                                        size="small"
                                        fluid
                                    />
                                    <label for="on_label" class="bg-transparent! font-light!">{{ 'Страна' }}</label>
                                </FloatLabel>
                                <InputError :message="form.errors.country_id" />
                            </div>

                            <div>
                                <FloatLabel variant="on">
                                    <InputMask
                                        id="phone"
                                        v-model="form.phone"
                                        type="tel"
                                        class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                                        aria-labelledby="phone"
                                        size="small"
                                        :mask="mask"
                                        :aria-autocomplete="form.phone"
                                    />
                                    <label for="phone" class="bg-transparent! font-light!">{{ mask ?? 'Телефон' }}</label>
                                </FloatLabel>
                                <InputError :message="form.errors.phone" />
                            </div>
                        </div>
                    </InfoCard>
                </div>
            </template>
            <template #center-column>
                <ProfileCard>
                    <div class="mb-2 space-y-4">
                        <FloatLabel variant="on">
                            <Textarea
                                id="description"
                                v-model="form.description"
                                rows="1"
                                cols="15"
                                autoResize
                                size="small"
                                class="w-full !rounded-none !border-0 !border-b-1 !bg-transparent !shadow-none"
                            />
                            <label class="bg-transparent! font-light!" for="description">Описание</label>
                        </FloatLabel>
                        <InputError :message="form.errors.description" />
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
