<script setup lang="ts">
import FormDrawer from '@/components/branch/FormDrawer.vue';
import Show from '@/components/branch/Show.vue';
import DeleteConfirmation from '@/components/common/DeleteConfirmation.vue';
import Restore from '@/components/common/Restore.vue';
import { getInitials } from '@/composables/useInitials';
import { useMediaQuery } from '@vueuse/core';
import { workingWithTableItems } from '@/composables/workingWithTableItems';
import { Branch } from '@/types';
import { FilterMatchMode } from '@primevue/core/api';
import { computed, onBeforeMount, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';
import { useStatus } from '@/composables/useStatus';

const props = defineProps({
    branches: {
        type: Object,
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
const count = ref();
const selectedItems = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const loading = ref(true);
const pagination = ref(false);

const { useRows } = workingWithTableItems();


onBeforeMount(() => {
    items.value = props.branches;
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
    return items.value.every((item: Branch) => !!item.deleted_at);
});

const onDeleteItem = (id: Branch) => {
    useRows(items, ref([id]));
};
const onLoadItem = () => {
    items.value = props.branches;
};

const onRestoreItem = (id: Branch) => {
    useRows(items, ref([id]));
};

const onRestoreSelectedItems = () => {
    useRows(items, selectedItems);
};

const onDeleteSelectedItems = () => {
    useRows(items, selectedItems);
};

const getStatusInfo = (item: any) => {
    return computed(() => useStatus(item.status));
};
/*const getStatusLabel = (status: boolean) => {
    switch (status) {
        case true:
            return 'active';
        case false:
            return 'disabled';
        default:
            return undefined;
    } //FIXME вынести логику useComposible
};
const getStatusTag = (status: boolean) => {
    switch (status) {
        case true:
            return 'success';
        case false:
            return 'warn';
        default:
            return undefined;
    } //FIXME вынести логику useComposible
};*/
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
                end: 'w-full mt-3 sm:w-auto sm:mt-0',
            }"
        >
            <template #start>
                <div class="flex flex-row items-start">
                    <span class="">
                        <form-drawer
                            v-if="tools.create && !isLargeScreen"
                            @new-user="onLoadItem"
                            icon-name="pi pi-map-marker"
                            raised
                            label="New"
                            title="New branch"
                        />
                        <Button
                            v-if="tools.create && isLargeScreen"
                            as="a"
                            icon="pi pi-map-marker"
                            label="New"
                            raised
                            :href="route('branch.create')"
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
                            route="branch.bulk.restore"
                            :disabled="!selectedItems || !selectedItems.length"
                            @restore-items="onRestoreSelectedItems"
                        />
                        <delete-confirmation
                            :entity="selectedItems"
                            icon-name="pi pi-trash"
                            type="multi"
                            text="will be deleted. Employees have been removed from the branch."
                            delete-label-btn="Yes, delete branches"
                            :route="isDeleted ? 'branch.bulk.force' : 'branch.bulk.soft'"
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
                    <Button v-if="page.url === '/branch'" as="a" :href="route('branch.archive')" icon="pi pi-box" size="small"
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
            :globalFilterFields="['name', 'status', 'description', 'contact', 'created_at']"
            sortMode="multiple"
            removable-sort
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} items"
            :loading="loading"
        >
            <template #empty><p class="text-center text-xl font-bold">No remote branches</p></template>
            <template #loading>Uploading user data. Please wait.</template>
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
                        :label="slotProps.data.avatar ? slotProps.data.avatar : getInitials(slotProps.data.name)"
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
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white" :class="{ 'text-red-400!': !slotProps.data.status }">
                        {{ slotProps.data.name }}
                    </div>
                    <p>
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">ID: {{ slotProps.data.id }}</small>
                    </p>
                    <p>
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.created_at }}</small>
                    </p>
                </template>
            </Column>
            <Column
                field="country_id"
                header="Country"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'hidden md:table-cell md:max-w-[250px] md:truncate',
                    },
                }"
            >
                <template #body="slotProps">
                    <div class="flex flex-row flex-wrap">
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{slotProps.data.country.code}}</small>
                        <Divider layout="vertical" />
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{slotProps.data.country.iso_code}}</small>
                        <Divider layout="vertical" />
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{slotProps.data.country.phone_code}}</small>
                        <Divider layout="vertical" />
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{slotProps.data.country.currency}}</small>
                    </div>
                </template>
            </Column>
            <Column
                field=""
                header="Info"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'sm:hidden',
                    },
                }"
            >
                <template #body="slotProps">
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white"></div>
                    <div class="mt-3 flex items-center justify-between text-xs font-normal text-gray-900 dark:text-gray-300">
                        <SpeedDial
                            :model="[{ command: () => {} }]"
                            direction="right"
                            :radius="20"
                            class="relative items-center"
                            buttonClass="!max-w-[1.5rem] !max-h-[1.5rem] !shadow-md"
                        >
                            <template #icon>
                                <i class="pi pi-cog"></i>
                            </template>
                            <template #item>
                                <restore
                                    v-if="tools.restore"
                                    :entity="slotProps.data"
                                    icon-name="pi pi-replay"
                                    route="branch.restore"
                                    @restore-item="onRestoreItem"
                                />
                                <form-drawer
                                    v-if="tools.update"
                                    icon-name="pi pi-pencil"
                                    label=""
                                    :branch="slotProps.data"
                                    @update-user="onLoadItem"
                                    variant="link"
                                />
                                <show :branch="slotProps.data" icon-name="pi pi-search" label="" route="" />
                                <delete-confirmation
                                    v-if="tools.remove"
                                    :entity="slotProps.data"
                                    icon-name="pi pi-trash"
                                    :route="isDeleted ? 'branch.force' : 'branch.soft.delete'"
                                    @delete-item="onDeleteItem"
                                />
                            </template>
                        </SpeedDial>
                    </div>
                </template>
            </Column>
            <Column
                field="status"
                header="Status"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'hidden xl:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    <Tag :value="getStatusInfo(slotProps.data).value.label"
                         :severity="getStatusInfo(slotProps.data).value.severity"
                    class="shadow-md"/>
                </template>
            </Column>
            <Column
                field="description"
                header="Description"
                :pt="{
                    root: {
                        class: 'hidden lg:table-cell max-w-[250px] lg:truncate',
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
                            icon="pi pi-pencil"
                            label=""
                            :href="route('branch.edit', slotProps.data)"
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
                            icon="pi pi-search"
                            label=""
                            :href="route('branch.show', slotProps.data)"
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
                            route="branch.restore"
                            @restore-item="onRestoreItem"
                        />
                        <delete-confirmation
                            v-if="tools.remove"
                            :entity="slotProps.data"
                            icon-name="pi pi-trash"
                            :route="isDeleted ? 'branch.force' : 'branch.soft.delete'"
                            text="will be moved to the basket. Employees will be removed from the branch"
                            delete-label-btn="Yes, delete branch!"
                            @delete-item="onDeleteItem"
                        />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
