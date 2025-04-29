<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogClose, DialogContentWithoutBtnClose, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserAvatarUpload from '@/components/UserAvatarUpload.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Toaster, toast } from 'vue-sonner';

const nameInput = ref<HTMLInputElement | null>(null);
const surnameInput = ref<HTMLInputElement | null>(null);
const middleNameInput = ref<HTMLInputElement | null>(null);
const phoneInput = ref<HTMLInputElement | null>(null);
const emailInput = ref<HTMLInputElement | null>(null);
const sourceInput = ref<HTMLInputElement | null>(null);
const commentInput = ref<HTMLInputElement | null>(null);
const discountInput = ref<HTMLInputElement | null>(null);

const wait = () => new Promise((resolve) => setTimeout(resolve, 1000));
const open = ref(false);

const message=ref('');

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

const submit = (e: Event) => {
    e.preventDefault();

    form.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: function () {
            wait().then(() => (open.value = false));
            closeModal();
        },
        onFinish: function () {
            if (!form.hasErrors) {
                toast.success('Client saved successfully.');
            }
        },
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};

const onUpdateAvatar=(data)=>{
    form.avatar = data.url;
    message.value = data.message;
    toast.success(data.message);
}



</script>

<template>
    <div class="relative z-10 space-y-6">
        <Dialog v-model:open="open">
            <DialogTrigger as-child>
                <Button variant="default" class="cursor-pointer bg-sky-600 hover:bg-sky-800" title="xxx">
                    <icon name="UserPlus" />
                    Добавить клиента
                </Button>
            </DialogTrigger>
            <DialogContentWithoutBtnClose class="rounded-none lg:min-w-[640px]">
                <DialogTitle className="sr-only">Добавить нового клиента</DialogTitle>
                <DialogHeader class="h-[47px] bg-[#1b2133] p-7 text-white">Добавить нового клиента</DialogHeader>
                <form @submit="submit" class="p-3.5">
                    <div class="parent grid gap-x-[6px] gap-y-[6px] md:grid-cols-[repeat(3,_1fr)] md:grid-rows-[repeat(5,_auto)]">
                        <div class="div1 [grid-area:1_/_1_/_2_/_2]">
                            <div class="max-h-[200px] max-w-[180px] rounded-[4px] bg-[#83BCE1] p-4 shadow-md">
                                <UserAvatarUpload text-add="Добавить фото"
                                                  text-delete="Удалить фото"
                                                  @updateAvatar="onUpdateAvatar"

                                />

                            </div>
                            <InputError :message="form.errors.avatar" class="my-2" />
                        </div>

                        <div class="div2 sm:ml-2 md:ml-2 md:space-y-4 md:[grid-area:1_/_2_/_2_/_3] lg:space-y-6">
                            <Label for="name" class="mb-0.5 text-[13px] font-normal text-[#555]">Имя:</Label>
                            <Input
                                id="name"
                                name="name"
                                ref="nameInput"
                                v-model="form.name"
                                type="text"
                                placeholder="Имя"
                                class="mb-[6px] h-7 rounded-[4px] border-[1px] border-solid border-[#ccc] bg-[#fff] px-[10px] py-[6px] [box-shadow:inset_0_1px_1px_rgba(0,_0,_0,_0.07)]"
                            />
                            <InputError :message="form.errors.name" class="mb-2" />
                            <Label for="surname" class="mb-0.5 text-[13px] font-normal text-[#555]">Фамилия:</Label>
                            <Input
                                id="surname"
                                name="surname"
                                ref="surnameInput"
                                v-model="form.surname"
                                type="text"
                                placeholder="Фамилия"
                                class="mb-[6px] h-7 rounded-[4px] border-[1px] border-solid border-[#ccc] bg-[#fff] px-[10px] py-[6px] [box-shadow:inset_0_1px_1px_rgba(0,_0,_0,_0.07)]"
                            />
                            <InputError :message="form.errors.surname" class="mb-2" />
                            <Label for="middleName" class="mb-0.5 text-[13px] font-normal text-[#555]">Отчество: </Label>
                            <Input
                                id="middleName"
                                name="middleName"
                                ref="middleNameInput"
                                v-model="form.middleName"
                                type="text"
                                placeholder="Отчество"
                                class="mb-[6px] h-7 rounded-[4px] border-[1px] border-solid border-[#ccc] bg-[#fff] px-[10px] py-[6px] [box-shadow:inset_0_1px_1px_rgba(0,_0,_0,_0.07)]"
                            />
                            <InputError :message="form.errors.middleName" class="mb-2" />
                        </div>
                        <div class="div3 sm:ml-2 md:ml-2 md:space-y-4 md:[grid-area:1_/_3_/_2_/_4] lg:space-y-6">
                            <Label for="phone" class="mb-0.5 text-[13px] font-normal text-[#555]">Телефон</Label>
                            <Input
                                id="phone"
                                name="phone"
                                ref="phoneInput"
                                v-model="form.phone"
                                type="tel"
                                placeholder="Телефон"
                                pattern="[0-9]*"
                                class="mb-[6px] h-7 rounded-[4px] border-[1px] border-solid border-[#ccc] bg-[#fff] px-[10px] py-[6px] [box-shadow:inset_0_1px_1px_rgba(0,_0,_0,_0.07)]"
                            />
                            <InputError :message="form.errors.phone" class="mb-2" />
                            <Label for="email" class="mb-0.5 text-[13px] font-normal text-[#555]">Email:</Label>
                            <Input
                                id="email"
                                name="email"
                                ref="emailInput"
                                v-model="form.email"
                                type="email"
                                placeholder="Email"
                                class="mb-[6px] h-7 rounded-[4px] border-[1px] border-solid border-[#ccc] bg-[#fff] px-[10px] py-[6px] [box-shadow:inset_0_1px_1px_rgba(0,_0,_0,_0.07)]"
                            />
                            <InputError :message="form.errors.email" class="mb-2" />
                            <Label for="source" class="mb-0.5 text-[13px] font-normal text-[#555]">Источник</Label>
                            <Input
                                id="source"
                                name="source"
                                ref="sourceInput"
                                v-model="form.source"
                                type="text"
                                placeholder="Источник"
                                class="mb-[6px] h-7 rounded-[4px] border-[1px] border-solid border-[#ccc] bg-[#fff] px-[10px] py-[6px] [box-shadow:inset_0_1px_1px_rgba(0,_0,_0,_0.07)]"
                            />
                            <InputError :message="form.errors.source" class="mb-2" />
                        </div>
                        <div class="div4 md:[grid-area:2_/_1_/_3_/_4]">
                            <Label for="comment" class="mb-0.5 text-[13px] font-normal text-[#555]">Заметка</Label>
                            <textarea
                                name="comment"
                                id="comment"
                                cols="15"
                                rows="4"
                                class="w-full resize-none rounded-[4px] border-[1px] border-solid border-[#ccc] p-1"
                                ref="commentInput"
                                v-model="form.comment"
                            ></textarea>
                            <InputError :message="form.errors.comment" class="mb-2" />
                        </div>
                        <div class="div5 mb-2 md:[grid-area:3_/_1_/_4_/_4]">
                            <Label for="discount" class="mb-0.5 text-[13px] font-normal text-[#555]">Персональная скидка</Label>
                            <Input
                                id="discount"
                                name="discount"
                                ref="discountInput"
                                v-model="form.discount"
                                type="text"
                                placeholder="Персональная скидка"
                                class="mb-[6px] h-7 rounded-[4px] border-[1px] border-solid border-[#ccc] bg-[#fff] px-[10px] py-[6px] [box-shadow:inset_0_1px_1px_rgba(0,_0,_0,_0.07)]"
                            />
                            <InputError :message="form.errors.discount" class="mb-2" />
                        </div>
                        <div class="div6 space-y-1 md:[grid-area:4_/_1_/_5_/_4]">
                            <Label for="blackList" class="mb-0.5 flex items-center gap-0 space-x-3 text-[13px] font-normal text-[#555]">
                                <Checkbox
                                    id="blackList"
                                    v-model="form.blacklist"
                                    :tabindex="4"
                                    name="blacklist"
                                    class="data-[state=checked]:border-sky-700 data-[state=checked]:bg-sky-600"
                                />
                                <span>В черном списке</span>
                            </Label>

                            <Label for="alwaysPrepayment" class="mb-0.5 flex items-center gap-0 space-x-3 text-[13px] font-normal text-[#555]">
                                <Checkbox
                                    id="alwaysPrepayment"
                                    v-model="form.prepayment"
                                    :tabindex="4"
                                    name="prepayment"
                                    class="data-[state=checked]:border-sky-700 data-[state=checked]:bg-sky-600"
                                />
                                <span>Всегда по предоплате</span>
                            </Label>
                        </div>
                        <div class="div7 md:[grid-area:5_/_2_/_6_/_4]">
                            <div class="flex flex-row justify-end gap-2">
                                <Button
                                    variant="default"
                                    :disabled="form.processing"
                                    class="border-color[#349136] hover:border-color[#317733] h-[30px] cursor-pointer rounded-sm bg-[#349136] py-1.5 font-normal text-white transition-colors hover:bg-[#317733]"
                                >
                                    <Toaster position="top-right" closeButton richColors />
                                    <Icon name="Save" />
                                    Сохранить
                                </Button>
                                <DialogClose as-child>
                                    <Button variant="secondary" @click="closeModal" class="h-[30px] cursor-pointer rounded-sm py-1.5 font-normal">
                                        <Toaster position="top-right" closeButton />
                                        Отмена
                                    </Button>
                                </DialogClose>
                            </div>
                        </div>
                    </div>
                    <!--                        <div class="h-[200px] w-[180px] rounded-[4px] bg-[#83BCE1] p-4 shadow-md">
                                                <Label for="upload" class="flex cursor-pointer flex-col items-center gap-2">
                                                    <img src="../../../public/no_avatar_big.png" alt="" />
                                                    <span class="font-medium text-white transition-colors hover:text-gray-900">Добавить фото</span>
                                                </Label>
                                                <input id="upload" type="file" class="hidden" />
                                            </div>-->
                    <!--                    <section class="my-auto py-10 dark:bg-gray-900">
                                            <div class="mx-auto flex w-[96%] gap-4 md:w-[90%] lg:w-[80%]">
                                                <div class="mx-auto h-fit w-full self-center rounded-xl p-4 shadow-2xl sm:w-[88%] lg:w-[88%] dark:bg-gray-800/40">
                                                    &lt;!&ndash;  &ndash;&gt;
                                                    <div class="">
                                                        <h1 class="mb-2 font-serif text-xl font-extrabold md:text-2xl lg:text-3xl dark:text-white">Profile</h1>
                                                        <h2 class="text-grey mb-4 text-sm dark:text-gray-400">Create Profile</h2>
                                                        <form>
                                                            &lt;!&ndash; Cover Image &ndash;&gt;
                                                            <div
                                                                class="w-full items-center rounded-sm bg-[url('https://images.unsplash.com/photo-1449844908441-8829872d2607?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw2fHxob21lfGVufDB8MHx8fDE3MTA0MDE1NDZ8MA&ixlib=rb-4.0.3&q=80&w=1080')] bg-cover bg-center bg-no-repeat"
                                                            >
                                                                &lt;!&ndash; Profile Image &ndash;&gt;
                                                                <div
                                                                    class="mx-auto flex h-[141px] w-[141px] justify-center rounded-full bg-blue-300/20 bg-[url('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw4fHxwcm9maWxlfGVufDB8MHx8fDE3MTEwMDM0MjN8MA&ixlib=rb-4.0.3&q=80&w=1080')] bg-cover bg-center bg-no-repeat"
                                                                >
                                                                    <div class="mt-4 ml-28 h-6 w-6 rounded-full bg-white/90 text-center">
                                                                        <input type="file" name="profile" id="upload_profile" hidden required />
                    
                                                                        <label for="upload_profile">
                                                                            <svg
                                                                                data-slot="icon"
                                                                                class="h-5 w-6 text-blue-700"
                                                                                fill="none"
                                                                                stroke-width="1.5"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                aria-hidden="true"
                                                                            >
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"
                                                                                ></path>
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"
                                                                                ></path>
                                                                            </svg>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-end">
                                                                    &lt;!&ndash;  &ndash;&gt;
                                                                    <input type="file" name="profile" id="upload_cover" hidden required />
                    
                                                                    <div class="flex items-center gap-1 rounded-tl-md bg-white px-2 text-center font-semibold">
                                                                        <label for="upload_cover" class="inline-flex cursor-pointer items-center gap-1"
                                                                            >Cover
                    
                                                                            <svg
                                                                                data-slot="icon"
                                                                                class="h-5 w-6 text-blue-700"
                                                                                fill="none"
                                                                                stroke-width="1.5"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                aria-hidden="true"
                                                                            >
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"
                                                                                ></path>
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"
                                                                                ></path>
                                                                            </svg>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h2 class="mt-1 text-center font-semibold dark:text-gray-300">Upload Profile and Cover Image</h2>
                                                            <div class="flex w-full flex-col justify-center gap-2 lg:flex-row">
                                                                <div class="mt-6 mb-4 w-full">
                                                                    <label for="" class="mb-2 dark:text-gray-300">First Name</label>
                                                                    <input
                                                                        type="text"
                                                                        class="mt-2 w-full rounded-lg border-2 p-4 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
                                                                        placeholder="First Name"
                                                                    />
                                                                </div>
                                                                <div class="mb-4 w-full lg:mt-6">
                                                                    <label for="" class="dark:text-gray-300">Last Name</label>
                                                                    <input
                                                                        type="text"
                                                                        class="mt-2 w-full rounded-lg border-2 p-4 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
                                                                        placeholder="Last Name"
                                                                    />
                                                                </div>
                                                            </div>
                    
                                                            <div class="flex w-full flex-col justify-center gap-2 lg:flex-row">
                                                                <div class="w-full">
                                                                    <h3 class="mb-2 dark:text-gray-300">Sex</h3>
                                                                    <select
                                                                        class="text-grey w-full rounded-lg border-2 p-4 pr-2 pl-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
                                                                    >
                                                                        <option disabled value="">Select Sex</option>
                                                                        <option value="Male">Male</option>
                                                                        <option value="Female">Female</option>
                                                                    </select>
                                                                </div>
                                                                <div class="w-full">
                                                                    <h3 class="mb-2 dark:text-gray-300">Date Of Birth</h3>
                                                                    <input
                                                                        type="date"
                                                                        class="text-grey w-full rounded-lg border-2 p-4 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
                                                                    />
                                                                </div>
                                                            </div>
                                                            <div class="mt-4 w-full rounded-lg bg-blue-500 text-lg font-semibold text-white">
                                                                <button type="submit" class="w-full p-4">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>-->
                </form>
            </DialogContentWithoutBtnClose>
        </Dialog>
    </div>
</template>
