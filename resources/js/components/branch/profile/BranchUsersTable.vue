<script setup lang="ts">
import { onMounted, PropType, ref } from 'vue';
import Toast from 'primevue/toast';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import { Branch, User } from '@/types';
import UnsubscribeConfirmation from '@/components/branch/profile/UnsubscribeConfirmation.vue';
import { useRows } from '@/composables/workingWithTableItems';
import { getPhone } from '@/composables/usePhoneLink';
import { useMediaQuery } from '@vueuse/core';

const props = defineProps({
    branch: {
        type: Object as PropType<Branch>,
        required: true,
    },
    usersList: {
        type: Object as PropType<User | null>,
    },
});

const toast = useToast();

// Состояние
const channel = ref();
const users = ref();
const selectedUsers = ref(); // для массового удаления
const loading = ref(false);

// Загрузка данных
const loadUsers = () => {
    loading.value = true;
    try {
        users.value = props.usersList;
        channel.value = props.branch;
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Ошибка',
            detail: 'Не удалось загрузить пользователей' + error,
            life: 5000,
        });
    }
    loading.value = false;
};


// Автозагрузка при монтировании
onMounted(() => {
    loadUsers();
});

const onUnscribeSelectedItems = () => {
    useRows(users, selectedUsers);
};

const onUnscribeItem = (id: Branch) => {
    useRows(users, ref([id]));
};

const isLargeScreen = useMediaQuery('(min-width: 640px)');
</script>

<template>
    <Card class="w-full rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <Toast />
            <Toolbar
                v-if="isLargeScreen"
                class="mb-6"
                :pt="{
                    end: 'w-full mt-3 sm:w-auto sm:mt-0',
                }"
            >
                <template #start>
                    <div class="flex flex-row items-start">
                        <!-- Кнопка массового удаления -->
                        <unsubscribe-confirmation
                            :subscriber="selectedUsers"
                            :channel="channel"
                            icon-name="pi pi-user-minus"
                            type="multi"
                            route="branch.unsubscribe"
                            :disabled="!selectedUsers || !selectedUsers.length"
                            @unscribe-items="onUnscribeSelectedItems"
                        />
                    </div>
                </template>
            </Toolbar>

            <!-- Таблица пользователей -->
            <DataTable
                :value="users"
                v-model:selection="selectedUsers"
                dataKey="id"
                :loading="loading"
                paginator
                :rows="10"
                :rowsPerPageOptions="[5, 10, 20]"
                :rowsPerPageLabel="'Строк на страницу'"
            >
                <template #empty><p class="text-center text-xl font-bold">No employees assigned</p></template>
                <template #loading>Uploading user data. Please wait.</template>
                <!-- Чекбокс для выбора (для массового удаления) -->
                <Column
                    selectionMode="multiple"
                    :exportable="false"
                    :pt="{
                        root: {
                            class: 'hidden sm:table-cell',
                        },
                        pcRowCheckbox: {
                            root: { class: 'shadow-md' },
                            input: { name: 'selectedUsers' },
                        },
                        pcHeaderCheckbox: {
                            root: { class: 'shadow-md' },
                            input: { name: 'allSelected' },
                        },
                    }"
                ></Column>

                <!-- Имя пользователя -->
                <Column field="name" header="Имя" :sortable="true"></Column>

                <!-- Email -->
                <Column field="phone" header="Phone" :sortable="true" v-if="isLargeScreen">
                    <template #body="{ data }">
                        <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white">
                            <Button class="!px-0" as="a" variant="link" :label="data.phone" :href="'tel:' + getPhone(data.phone)" rel="noopener" />
                        </div>
                    </template>
                </Column>

                <!-- Действия (одиночное удаление) -->
                <Column header="Tools" class="!text-center">
                    <template #body="{ data }">
                        <unsubscribe-confirmation
                            :subscriber="data"
                            :channel="channel"
                            icon-name="pi pi-user-minus"
                            route="branch.unsubscribe"
                            :disabled="!selectedUsers || !selectedUsers.length"
                            @unscribe-item="onUnscribeItem"
                        />
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
</template>

<style scoped></style>
