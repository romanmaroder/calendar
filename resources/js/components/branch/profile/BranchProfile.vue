<script setup lang="ts">
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Branch } from '@/types';
import { computed, PropType, ref } from 'vue';
import InfoCard from './InfoCard.vue';
import ProfileCard from './ProfileCard.vue';
import { useMediaQuery } from '@vueuse/core';
import BranchUsersTable from '@/components/branch/profile/BranchUsersTable.vue';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
        required: true,
    },
});

/* demo data */
const patient = {
    name: 'Кейт Прокопчук',
    //avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=60',
    avatar: '/no_avatar_big.png',
    phone: '+38 (093) 23 45 678',
    phoneRaw: '+380932345678',
    email: 'katepro@gmail.com',
    dob: '23. 07. 1994',
    address: 'Львов, ул. Черновола, 67',
    registered: 'Четверг, 25 мая',
};

const salaryData = {
    month: '80000',
    currentDay: '4250',
};

const visitsFuture = [
    {
        time: '11.00-12.30',
        date: '26 Июн 2023',
        service: 'Лечение и чистка каналов',
        master: 'Юля Шевцова',
        status: 'Запланировано',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Отбеливание зубов',
        master: 'Юля Шевцова',
        status: 'Запланировано',
    },
];

const visitsPast = [
    {
        time: '11.00-12.30',
        date: '26 Июн 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Завершен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Отменен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Завершен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Завершен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Отменен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Завершен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Отменен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Завершен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'Завершен',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (пальцы)',
        master: 'Юля Шевцова',
        status: 'Завершен',
    },
];

const visitsCurrent = [
    {
        time: '11.00-12.30',
        date: '26 Июн 2023',
        service: 'Педикюр (покрытие)',
        master: 'Юля Шевцова',
        status: 'На обслуживании',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (стопа)',
        master: 'Юля Шевцова',
        status: 'На обслуживании',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'На обслуживании',
    },
    {
        time: '11.00-12.30',
        date: '27 Июл 2023',
        service: 'Педикюр (полностью)',
        master: 'Юля Шевцова',
        status: 'На обслуживании',
    },
];

const tabs = [
    { key: 'planned', label: 'Текущие записи', count: '' },
    { key: 'future', label: 'Будущие записи', count: '(2)' },
    { key: 'past', label: 'Прошлые записи', count: '(100)' },
];

const files = ref([
    { name: 'Результат обследования.pdf', size: '123kb', highlight: false },
    { name: 'Результат обследования.pdf', size: '123kb', highlight: true },
    { name: 'Медицинские предписания.pdf', size: '123kb', highlight: false },
]);

const notes = ref([
    { title: 'Примечание 31.06.23.pdf', size: '123kb' },
    { title: 'Примечание 23.06.23.pdf', size: '123kb' },
]);

function downloadFile(file: any) {
    alert(`Скачиваем: ${file.name}`);
}

function removeFile(index: number) {
    files.value.splice(index, 1);
}

function downloadNote(note: any) {
    alert(`Скачиваем заметку: ${note.title}`);
}

const isMobile = useMediaQuery('(max-width: 640px)');

const visible = computed(() => {
    return props.branch.users.length > 0;
});

const items = ref([
    {
        label: 'Edit',
        icon: 'pi pi-pencil',
        command: () => {
            try {
                window.location.href = route('branch.edit', props.branch.id);
            } catch (error) {
                console.error('Маршрут не найден:', error);
            }
        },
    },
    {
        label: 'Branches',
        icon: 'pi pi-map-marker',
        command: () => {
            try {
                window.location.href = route('branch.index');
            } catch (error) {
                console.error('Маршрут не найден:', error);
            }
        },
    },
]);
</script>

<template>
    <ProfileLayout>
        <template #left-center-column>
            <ProfileCard :branch="branch" />
        </template>

        <template #right-center-column>
            <InfoCard :branch="branch" title="Общая информация" />
        </template>
        <template #center-column v-if="visible">
            <BranchUsersTable :branch="branch" :users-list="branch.users" />
            <ContextMenu global :model="items" class="mobile-area"/>
        </template>
    </ProfileLayout>
</template>

<style scoped></style>
