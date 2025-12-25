<!--Contains a form for creating/editing a branch.
Notifies the branch about creation/editing and returns the data for display in the preview.-->
<script setup lang="ts">
import AvatarUploader from '@/components/AvatarUploader.vue';
import InputError from '@/components/InputError.vue';
import InfoCard from '@/components/branch/profile/InfoCard.vue';
import ProfileCard from '@/components/branch/profile/ProfileCard.vue';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Branch } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { PropType, watch } from 'vue';

const emit = defineEmits(['createUser', 'updateUser', 'drawerData']);

const props = defineProps({
    branch: Object as PropType<Branch | null>,
});

const toast = useToast();

const form = useForm({
    id: props.branch?.id ?? '',
    name: props.branch?.name ?? '',
    description: props.branch?.description ?? '',
    contact: props.branch?.contact ?? '',
    avatar: props.branch?.avatar ?? '',
    status: props.branch?.status ?? '',
    created_at: props.branch?.created_at ?? '',
});

watch(form, () => {
    emit('drawerData', { name: form.name, avatar: form.avatar });
});

const onUpdateCropped = (value: string) => {
    form.avatar = value;
    console.log('FORM ' + form.avatar, 'URL ' + value);
};
const submit = () => {
    if (form.id) {
        form.put(route('branch.update', { id: form.id }), {
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
        form.post(route('branch.store'), {
            preserveScroll: true,
            onSuccess: function () {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: form.name + ' - add successfully.',
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
        form.put(route('branch.avatar', { id: form.id }), {
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
                    </div>
                </ProfileCard>
            </template>
            <template #right-center-column>
                <div class="mb-2 space-y-4">
                    <InfoCard title="Общая инфа">
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
                                        v-keyfilter="/^[A-zА-яёЁ\s]+$/iu"
                                    />
                                    <label for="name" class="bg-transparent! font-light!">{{ 'Контакты:' }}</label>
                                </FloatLabel>
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="flex items-center gap-2">
                                <Checkbox v-model="form.status" inputId="status" name="status" size="small" :tabindex="4" binary />
                                <label for="active"> Статус </label>
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
