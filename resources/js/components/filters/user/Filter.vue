<script setup lang="ts">
import type { SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import FloatLabel from 'primevue/floatlabel';
import InputIcon from 'primevue/inputicon';
import InputMask from 'primevue/inputmask';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import SplitButton from 'primevue/splitbutton';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const parseUrl = (str: any) => {
    return str
        .split(/[?#]/)[0]
        .replace(/[^\w ]/, '')
        .replace(/\//g, '.');
};

const props = defineProps({
    entities: {
        type: Object,
        default() {
            return {};
        },
    },
    className: String,
});

const page = usePage<SharedData>();
const currentUrl = parseUrl(page.url);

const toast = useToast();
const classAttr = ref('grid items-end gap-5 mt-2 sm:grid-cols-2 md:grid-cols-3 md:gap-3 lg:grid-cols-6 ');

const form = useForm({
    id: null,
    name: null,
    surname: null,
    middleName: null,
    phone: null,
    email: null,
});

const submit = (e: Event) => {
    e.preventDefault();
    if (form.id || form.name || form.surname || form.middleName || form.phone || form.email) {
        form.get(route(currentUrl), {
            preserveScroll: true,
            onSuccess: function (page) {
                if (page.props.clients?.data.length < 1) {
                    toast.add({
                        severity: 'error',
                        summary: 'The client was not found.',
                        detail: 'The client was not found.',
                        life: 3000,
                    });
                } else {
                    toast.add({
                        severity: 'success',
                        summary: 'The client was found',
                        detail: page.props.clients?.data[0].name + ' ' + page.props.clients?.data[0].surname,
                        life: 3000,
                    });
                }
            },
            onFinish: function () {},
        });
    } else {
        toast.add({
            severity: 'warn',
            summary: 'Warning Message',
            detail: 'At least one field should be filled.',
            life: 3000,
        });
    }
};
const items = [
    {
        label: 'Сбросить',
        icon: 'pi pi-sync',
        command: () => {
            window.location.href = route(currentUrl);
        },
    },
];

const disabled = ref(false);

onMounted(() => {
    if (props.entities.data?.length == 1) {
        Object.entries(props.entities.data[0]).forEach((entry) => {
            const [key, value] = entry;
            form[`${key}`] = value;
        });
    }
    if (form.id != null) {
        disabled.value = true;
    }
    if (props.className) {
        classAttr.value = props.className;
    }
});
</script>

<template>
    <form>
        <div :class="classAttr">
            <FloatLabel variant="on" class="sm:order-1 md:order-1">
                <InputNumber
                    v-model="form.id"
                    input-id="id"
                    autocomplete="off"
                    class="h-[28px] w-full"
                    aria-labelledby="id"
                    :disabled="disabled"
                    size="large"
                    inputClass="w-full"
                />
                <label for="id" class="font-light!">ID</label>
            </FloatLabel>
            <FloatLabel variant="on" class="sm:order-3 md:order-2">
                <InputText
                    id="name"
                    v-model="form.name"
                    autocomplete="off"
                    class="h-[28px] w-full"
                    aria-labelledby="name"
                    :disabled="disabled"
                    size="large"
                />
                <label for="name" class="font-light!">Имя:</label>
            </FloatLabel>
            <FloatLabel variant="on" class="sm:order-5 md:order-3">
                <InputText
                    id="surname"
                    v-model="form.surname"
                    autocomplete="off"
                    class="h-[28px] w-full"
                    aria-labelledby="surname"
                    :disabled="disabled"
                    size="large"
                />
                <label for="surname" class="font-light!">Фамилия:</label>
            </FloatLabel>
            <FloatLabel variant="on" class="sm:order-2 md:order-4">
                <InputMask
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="h-[28px] w-full"
                    aria-labelledby="phone"
                    size="small"
                    ref="phoneInput"
                    mask="+9 999 999 99 99"
                />
                <label for="phone" class="font-light!">Телефон:</label>
            </FloatLabel>
            <FloatLabel variant="on" class="sm:order-4 md:order-5">
                <InputText
                    id="email"
                    v-model="form.email"
                    type="email"
                    autocomplete="off"
                    class="h-[28px] w-full"
                    aria-labelledby="email"
                    :disabled="disabled"
                    size="large"
                />
                <label for="email" class="font-light!">Email:</label>
            </FloatLabel>
            <SplitButton
                @click="submit"
                :model="items"
                size="small"
                :disabled="form.processing"
                severity="success"
                :pt="{
                    root: 'w-full row-end-auto order-6',
                    label: 'text-lg! text-violet-500! dark:text-violet-400!',
                    pcButton: {
                        root: 'border-none! h-[30px]! cursor-pointer!  py-1.5! font-normal! text-white! transition-colors!',
                    },
                    pcMenu: {
                        itemLabel: 'text-sm!',
                        root: 'min-w-[150px]!',
                        rootList: 'px-0! py-0!',
                        item: '',
                        itemLink: 'py-1!',
                        itemContent: '',
                        itemIcon: 'text-dark! text-xs! dark:text-white!',
                    },
                    pcDropdown: {
                        root: 'border-none! h-[30px]! cursor-pointer!  py-1.5! font-normal! text-white! transition-colors!',
                    },
                }"
            >
                <div class="flex items-center justify-between">
                    <InputIcon class="pi pi-search relative! top-0! mt-0! mr-3! text-[0.8rem]! text-white!" />
                    {{ form.processing ? 'Ищем...' : 'Фильтр' }}
                </div>
            </SplitButton>
        </div>
    </form>
</template>
