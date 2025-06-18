<script setup lang="ts">
import AvatarUpload from '@/components/AvatarUpload.vue';
import Icon from '@/components/Icon.vue';
import InputError from '@/components/InputError.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogClose, DialogContentWithoutBtnClose, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { ref } from 'vue';

const nameInput = ref<HTMLInputElement | null>(null);
const surnameInput = ref<HTMLInputElement | null>(null);
const middleNameInput = ref<HTMLInputElement | null>(null);
const phoneInput = ref<HTMLInputElement | null>(null);
const emailInput = ref<HTMLInputElement | null>(null);
const sourceInput = ref<HTMLInputElement | null>(null);
const commentInput = ref<HTMLInputElement | null>(null);
const discountInput = ref<HTMLInputElement | null>(null);

const wait = () => new Promise((resolve) => setTimeout(resolve, 500));
const open = ref(false);

const toast = ref('');

const form = useForm({
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
});

const emit = defineEmits(['addCustomer']);

const submit = (e: Event) => {
    e.preventDefault();

    form.post(route('clients.store'), {
        preserveScroll: true,
        onSuccess: function () {
            wait().then(() => (open.value = false));
            emit('addCustomer', form.data());
            closeModal();
        },
        onFinish: function () {
            if (!form.hasErrors) {
            }
        },
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};

const onUpdateAvatar = (data: any) => {
    form.avatar = data.url;
    toast.value = data.message;
};
</script>

<template>
    <div class="relative z-10 space-y-6">
        <Dialog v-model:open="open">
            <DialogTrigger as-child>
                <Button class="cursor-pointer" size="small">
                    <Icon name="UserPlus" />
                    Добавить клиента
                </Button>
            </DialogTrigger>
            <DialogContentWithoutBtnClose class="rounded-none lg:min-w-[640px]">
                <DialogTitle className="sr-only">Добавить нового клиента</DialogTitle>
                <DialogHeader class="h-[47px] bg-[#1b2133] px-4 text-white">Добавить нового клиента</DialogHeader>
                <form class="p-3.5">
                    <div class="parent grid md:grid-cols-[repeat(3,_1fr)] md:grid-rows-[repeat(5,_auto)] md:gap-x-[10px] md:gap-y-[10px]">
                        <div class="mb-2 [grid-area:1_/_1_/_2_/_2] md:mb-0">
                            <div class="max-h-[200px] max-w-[180px] rounded-[4px] bg-[#83BCE1] p-4 shadow-md">
                                <AvatarUpload text-add="Добавить фото" text-delete="Удалить фото" updateUrl="avatar" @updateAvatar="onUpdateAvatar" />
                            </div>
                            <InputError :message="form.errors.avatar" class="my-2" />
                        </div>

                        <div class="my-2"></div>

                        <div class="space-y-2 md:space-y-4 md:[grid-area:1_/_2_/_2_/_3] lg:space-y-5">
                            <Label for="name" class="label-input-client-form">Имя:</Label>
                            <Input
                                id="name"
                                name="name"
                                ref="nameInput"
                                v-model="form.name"
                                type="text"
                                placeholder="Имя"
                                class="input-client-form"
                            />
                            <InputError :message="form.errors.name" class="mb-2" />
                            <Label for="surname" class="label-input-client-form">Фамилия:</Label>
                            <Input
                                id="surname"
                                name="surname"
                                ref="surnameInput"
                                v-model="form.surname"
                                type="text"
                                placeholder="Фамилия"
                                class="input-client-form"
                            />
                            <InputError :message="form.errors.surname" class="mb-2" />
                            <Label for="middleName" class="label-input-client-form">Отчество: </Label>
                            <Input
                                id="middleName"
                                name="middleName"
                                ref="middleNameInput"
                                v-model="form.middleName"
                                type="text"
                                placeholder="Отчество"
                                class="input-client-form"
                            />
                            <InputError :message="form.errors.middleName" class="mb-2" />
                        </div>
                        <div class="space-y-2 md:space-y-4 md:[grid-area:1_/_3_/_2_/_4] lg:space-y-5">
                            <Label for="phone" class="label-input-client-form">Телефон</Label>
                            <Input
                                id="phone"
                                name="phone"
                                ref="phoneInput"
                                v-model="form.phone"
                                type="tel"
                                placeholder="Телефон"
                                pattern="[0-9]*"
                                class="input-client-form"
                            />
                            <InputError :message="form.errors.phone" class="mb-2" />
                            <Label for="email" class="label-input-client-form">Email:</Label>
                            <Input
                                id="email"
                                name="email"
                                ref="emailInput"
                                v-model="form.email"
                                type="email"
                                placeholder="Email"
                                class="input-client-form"
                            />
                            <InputError :message="form.errors.email" class="mb-2" />
                            <Label for="source" class="label-input-client-form">Источник</Label>
                            <Input
                                id="source"
                                name="source"
                                ref="sourceInput"
                                v-model="form.source"
                                type="text"
                                placeholder="Источник"
                                class="input-client-form"
                            />
                            <InputError :message="form.errors.source" class="mb-2" />
                        </div>
                        <div class="md:[grid-area:2_/_1_/_3_/_4]">
                            <Label for="comment" class="label-input-client-form">Заметка</Label>
                            <textarea
                                name="comment"
                                id="comment"
                                cols="15"
                                rows="4"
                                class="textarea-client-form"
                                ref="commentInput"
                                v-model="form.comment"
                            ></textarea>
                            <InputError :message="form.errors.comment" class="mb-2" />
                        </div>
                        <div class="mb-2 md:[grid-area:3_/_1_/_4_/_4]">
                            <Label for="discount" class="label-input-client-form">Персональная скидка</Label>
                            <Input
                                id="discount"
                                name="discount"
                                ref="discountInput"
                                v-model="form.discount"
                                type="text"
                                placeholder="Персональная скидка"
                                class="input-client-form"
                            />
                            <InputError :message="form.errors.discount" class="mb-2" />
                        </div>
                        <div class="grid gap-y-1.5 md:[grid-area:4_/_1_/_5_/_4]">
                            <Label for="blackList" class="label-checkbox-client-form">
                                <Checkbox id="blackList" v-model="form.blacklist" :tabindex="4" name="blacklist" class="checkbox-client-form" />
                                <span>В черном списке</span>
                            </Label>

                            <Label for="alwaysPrepayment" class="label-checkbox-client-form">
                                <Checkbox
                                    id="alwaysPrepayment"
                                    v-model="form.prepayment"
                                    :tabindex="4"
                                    name="prepayment"
                                    class="checkbox-client-form"
                                />
                                <span>Всегда по предоплате</span>
                            </Label>
                        </div>
                        <div class="md:[grid-area:5_/_2_/_6_/_4]">
                            <div class="flex flex-row justify-end gap-2">
                                <Button
                                    @click="submit"
                                    severity="success"
                                    :disabled="form.processing"
                                    class="border-color[#349136] hover:border-color[#317733] h-[30px] cursor-pointer rounded-sm bg-[#349136] py-1.5 font-normal text-white transition-colors hover:bg-[#317733]"
                                >
                                    <Icon name="Save" />
                                    {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                                </Button>
                                <DialogClose as-child>
                                    <Button severity="secondary" @click="closeModal" class="h-[30px] cursor-pointer rounded-sm py-1.5 font-normal">
                                        Отмена
                                    </Button>
                                </DialogClose>
                            </div>
                        </div>
                    </div>
                </form>
            </DialogContentWithoutBtnClose>
        </Dialog>
    </div>
</template>
