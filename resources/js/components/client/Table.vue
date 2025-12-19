<script setup lang="ts">
import FormDrawer from '@/components/client/FormDrawer.vue';
import Show from '@/components/client/Show.vue';
import DeleteConfirmation from '@/components/common/DeleteConfirmation.vue';
import Restore from '@/components/common/Restore.vue';
import { getFullname } from '@/composables/useFullname';
import { getInitials } from '@/composables/useInitials';
import { useMediaQuery } from '@vueuse/core';
import { usePhoneLink } from '@/composables/usePhoneLink';
import { workingWithTableItems } from '@/composables/workingWithTableItems';
import { Client } from '@/types';
import { FilterMatchMode } from '@primevue/core/api';
import { computed, onBeforeMount, PropType, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';

//Типизация клиента

const props = defineProps({
    entities: {
        type: Object as PropType<Client>,
        required: true,
        default() {
            return {};
        },
    },
    tools: {
        type: Object,
        default() {
            return {
                create: false,
                update: false,
                restore: false,
                remove: false,
                show: false,
            };
        },
    },
});

const emit = defineEmits(['count']);

const page = usePage();

const dt = ref();
const items = ref();
const count = ref(0);
const selectedItems = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const formatCurrency = (value: any) => {
    return value?.toLocaleString('ru-RU', { style: 'currency', currency: 'RUB' });
};
const loading = ref(true);
const pagination = ref(false);

const { getPhone } = usePhoneLink();
const { useRows } = workingWithTableItems();

onBeforeMount(() => {
    items.value = props.entities;
    loading.value = false;
});

const isLargeScreen = useMediaQuery('(min-width: 640px)');

watch(items, () => {
    pagination.value = items.value.length > 0;
    count.value = items.value.length;
});

watch(count, () => {
    emit('count', count);
});

const isDeleted = computed(() => {
    return items.value.every((item: Client) => !!item.deleted_at);
});

const onDeleteItem = (id: Client) => {
    useRows(items, ref([id]));
};

const onLoadItem = () => {
    items.value = props.entities;
};

const onRestoreItem = (id: Client) => {
    useRows(items, ref([id]));
};

const onRestoreSelectedItems = () => {
    useRows(items, selectedItems);
};

const onDeleteSelectedItems = () => {
    useRows(items, selectedItems);
};

const getStatusLabel = (status: boolean) => {
    switch (status) {
        case true:
            return 'success';
        case false:
            return 'warn';
        default:
            return undefined;
    }
};

/*Для динамических таблиц
const filterFields = () => {
   if (props.filtersFields) {
       Object.entries(props.filtersFields).forEach(([key1, value1]) => {
           Object.entries(value1).forEach(([key2, value2]) => {
               if (key2 === 'field') {
                   fields.value.push(value2);
               }
           });
       });
   } else {
       Object.entries(props.columns).forEach(([key1, value1]) => {
           Object.entries(value1).forEach(([key2, value2]) => {
               if (key2 === 'field') {
                   fields.value.push(value2);
               }
           });
       });
   }
};*/
</script>

<template>
    <div class="grid auto-cols-fr">
        <Toolbar
            class="mb-6"
            :pt="{
                end: 'w-full mt-3 sm:w-auto sm:mt-0', //FIXME добавить в другие таблицы вместо фильтра
            }"
        >
            <template #start>
                <div class="flex flex-row items-start">
                    <span class="">
                        <form-drawer
                            v-if="tools.create && !isLargeScreen"
                            @new-user="onLoadItem"
                            icon-name="pi pi-user-plus"
                            raised
                            label="New"
                            title="New client"
                        />
                        <Button
                            v-if="tools.create && isLargeScreen"
                            as="a"
                            icon="pi pi-user-plus"
                            label="New"
                            raised
                            :href="route('clients.create')"
                            size="small"
                            class="mx-2"
                        />
                    </span>
                    <span class="hidden space-x-2 sm:flex">
                        <restore
                            v-if="tools.restore"
                            :entity="selectedItems"
                            label="Восстановить"
                            icon-name="pi pi-replay"
                            type="multi"
                            route="clients.bulk.restore"
                            :disabled="!selectedItems || !selectedItems.length"
                            @restore-items="onRestoreSelectedItems"
                        />
                        <delete-confirmation
                            :entity="selectedItems"
                            icon-name="pi pi-trash"
                            type="multi"
                            :route="isDeleted ? 'clients.bulk.force' : 'clients.bulk.soft'"
                            :disabled="!selectedItems || !selectedItems.length"
                            @delete-items="onDeleteSelectedItems"
                        />
                    </span>
                </div>
            </template>
            <template #end>
                <div class="flex space-x-2 w-full">
                    <IconField class="w-full rounded-md shadow-sm sm:w-auto">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" name="search" class="w-full sm:w-auto" placeholder="Search..." size="small" />
                    </IconField>
                    <Button v-if="page.url === '/clients'" as="a" :href="route('clients.archive')" icon="pi pi-box"
                            size="small"
                            severity="warn" raised variant="text" v-tooltip.bottom="'Archive'" label="ARC" />
                </div>
            </template>
        </Toolbar>

        <DataTable
            class="text-[15px]"
            ref="dt"
            v-model:selection="selectedItems"
            v-model:filters="filters"
            :value="items"
            dataKey="id"
            :paginator="pagination"
            :rows="10"
            filterDisplay="menu"
            :globalFilterFields="['name', 'surname', 'middleName', 'phone', 'email', 'comment', 'source', 'created_at', 'total']"
            sortMode="multiple"
            removable-sort
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 15, 20, 25]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} items"
            :loading="loading"
        >
            <template #empty><p class="text-center text-xl font-bold">Add clients</p></template>
            <template #loading> Loading items data. Please wait.</template>
            <Column
                selectionMode="multiple"
                :exportable="false"
                :pt="{
                    root: {
                        class: 'hidden sm:table-cell',
                    },
                    pcRowCheckbox: {
                        root: { class: 'shadow-md' },
                        input: { name: 'selectedItem' },
                    },
                    pcHeaderCheckbox: {
                        root: { class: 'shadow-md' },
                        input: { name: 'allSelected' },
                    },
                }"
            ></Column>
            <Column
                field="avatar"
                header="Avatar"
                :pt="{
                    root: {
                        class: 'hidden lg:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    <Avatar
                        v-if="!slotProps.data.avatar"
                        size="large"
                        class="shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]"
                        :label="
                            slotProps.data.avatar
                                ? slotProps.data.avatar
                                : getInitials(getFullname({ name: slotProps.data.name, surname: slotProps.data.surname }))
                        "
                    />

                    <Image
                        v-else
                        :src="slotProps.data.avatar"
                        :alt="slotProps.data.avatar"
                        preview
                        :pt="{
                            image: {
                                class: 'max-w-[48px] max-h-[48px] rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                            },
                        }"
                    />
                </template>
            </Column>
            <Column field="name" header="Name" :sortable="true">
                <template #body="slotProps">
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white">
                        {{
                            getFullname({
                                name: slotProps.data.name,
                                middlename: slotProps.data.middleName,
                                surname: slotProps.data.surname,
                            })
                        }}
                    </div>
                    <p>
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">ID: {{ slotProps.data.id }}</small>
                    </p>
                    <p class="hidden sm:table-cell">
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.created_at }}</small>
                    </p>
                </template>
            </Column>
            <Column
                field="phone"
                header="Info"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'sm:hidden',
                    },
                }"
            >
                <template #body="slotProps">
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white">
                        <Button
                            class="!px-0"
                            as="a"
                            variant="link"
                            :label="slotProps.data.phone"
                            :href="'tel:' + getPhone(slotProps.data.phone)"
                            rel="noopener"
                        />
                    </div>
                    <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.email }} </small>
                    <p class="sm:hidden">
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.created_at }}</small>
                    </p>
                    <div class="mt-3 flex items-center justify-between text-xs font-normal text-gray-900 dark:text-gray-300">
                        <SpeedDial
                            :model="[{ command: () => {} }]"
                            direction="right"
                            :radius="20"
                            class="relative items-center"
                            buttonClass="!max-w-[1.5rem] !max-h-[1.5rem]"
                        >
                            <template #icon>
                                <i class="pi pi-cog"></i>
                            </template>
                            <template #item>
                                <restore
                                    v-if="tools.restore"
                                    :entity="slotProps.data"
                                    icon-name="pi pi-replay"
                                    route="users.restore"
                                    @restore-item="onRestoreItem"
                                />
                                <form-drawer
                                    v-if="tools.update"
                                    icon-name="pi pi-user-edit"
                                    label=""
                                    :entity="slotProps.data"
                                    @update-user="onLoadItem"
                                    variant="link"
                                />
                                <show :entity="slotProps.data" icon-name="pi pi-user" label="" route="" />
                                <delete-confirmation
                                    v-if="tools.remove"
                                    :entity="slotProps.data"
                                    icon-name="pi pi-user-minus"
                                    :route="isDeleted ? 'clients.bulk.force' : 'clients.bulk.soft'"
                                    @delete-item="onDeleteItem"
                                />
                            </template>
                        </SpeedDial>
                    </div>
                </template>
            </Column>
            <Column
                field="phone"
                header="Phone/Email"
                :pt="{
                    root: {
                        class: 'hidden sm:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    <Button
                        class="!px-0"
                        as="a"
                        variant="link"
                        :label="slotProps.data.phone"
                        :href="'tel:' + getPhone(slotProps.data.phone)"
                        rel="noopener"
                    />
                    <p class="2xl:hidden">
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.email }}</small>
                    </p>
                </template>
            </Column>
            <Column
                field="discount"
                header="Discount"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'hidden lg:table-cell',
                    },
                }"
            ></Column>
            <Column
                field="total"
                header="Total"
                :pt="{
                    root: {
                        class: 'hidden lg:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    {{ formatCurrency(slotProps.data.total) }}
                </template>
            </Column>
            <Column
                field="email"
                header="Email"
                :pt="{
                    root: {
                        class: 'hidden 2xl:table-cell',
                    },
                }"
            ></Column>
            <Column
                field="blacklist"
                header="Blacklist"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'hidden xl:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    <Tag :value="slotProps.data.blacklist" :severity="getStatusLabel(slotProps.data.blacklist)" />
                </template>
            </Column>
            <Column
                field="prepayment"
                header="Prepayment"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'hidden xl:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    <Tag :value="slotProps.data.prepayment" :severity="getStatusLabel(slotProps.data.prepayment)" />
                </template>
            </Column>
            <Column
                field="comment"
                header="Comment"
                :pt="{
                    root: {
                        class: 'hidden lg:table-cell max-w-[250px] lg:truncate',
                    },
                }"
            ></Column>
            <Column
                field="source"
                header="Source"
                :pt="{
                    root: {
                        class: 'hidden 2xl:table-cell',
                    },
                }"
            ></Column>
            <Column
                :exportable="false"
                header="Tools"
                :pt="{
                    root: {
                        class: 'hidden sm:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    <span class="flex flex-row flex-wrap items-start justify-start">
                        <Button
                            v-if="tools.update"
                            as="a"
                            variant="link"
                            icon="pi pi-user-edit"
                            label=""
                            :href="route('clients.edit', slotProps.data)"
                            size="small"
                            :pt="{
                                icon: {
                                    class: 'mx-1 text-sky-600 hover:text-sky-900 focus:text-sky-900',
                                },
                            }"
                        />
                        <Button
                            as="a"
                            variant="link"
                            icon="pi pi-user"
                            label=""
                            :href="route('clients.show', slotProps.data)"
                            size="small"
                            :pt="{
                                icon: {
                                    class: 'mx-1 text-sky-600 hover:text-sky-900 focus:text-sky-900',
                                },
                            }"
                        />

                        <restore
                            v-if="tools.restore"
                            :entity="slotProps.data"
                            icon-name="pi pi-replay"
                            route="clients.restore"
                            @restore-item="onRestoreItem"
                        />
                        <delete-confirmation
                            v-if="tools.remove"
                            :entity="slotProps.data"
                            icon-name="pi pi-user-minus"
                            :route="isDeleted ? 'clients.force' : 'clients.soft.delete'"
                            @delete-item="onDeleteItem"
                        />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
