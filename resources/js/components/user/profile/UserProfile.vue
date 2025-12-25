<script setup lang="ts">
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { User } from '@/types';
import { PropType, ref } from 'vue';
import FilesList from './FilesList.vue';
import FinanceCard from './FinanceCard.vue';
import InfoCard from './InfoCard.vue';
import NotesList from './NotesList.vue';
import ProfileCard from './ProfileCard.vue';
import VisitsList from './VisitsList.vue';

const props =defineProps({
    user: {
        type: Object as PropType<User>,
        required:true,
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
const items = ref([
    {
        label: 'Edit',
        icon: 'pi pi-pencil',
        command: () => {
            try {
                window.location.href = route('users.edit', props.user.id);
            } catch (error) {
                console.error('Маршрут не найден:', error);
            }
        },
    },
    {
        label: 'Users',
        icon: 'pi pi-users',
        command: () => {
            try {
                window.location.href = route('users');
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
            <ProfileCard :user="user" />
        </template>

        <template #right-center-column>
            <InfoCard :user="user" title="Общая информация" />
        </template>

        <template #center-column>
            <VisitsList :tabs="tabs" :visits-future="visitsFuture" :visits-current="visitsCurrent" :visits-past="visitsPast" />
        </template>

        <template #right-column>
            <FinanceCard :data="salaryData" title="Зарплата" />
            <FilesList v-if="false" :files="files" title="Файлы" @download="downloadFile" @remove="removeFile" />
            <NotesList v-if="false" :notes="notes" title="Примечание" @download="downloadNote" />
            <ContextMenu global :model="items" class="mobile-area"/>
        </template>
    </ProfileLayout>
</template>

<style scoped></style>
