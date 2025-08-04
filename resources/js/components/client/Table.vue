<script setup lang="ts">
import Create from '@/components/client/Create.vue';
import CreateDialog from '@/components/client/CreateDialog.vue';
import DeleteDialog from '@/components/client/DeleteDialog.vue';
import MultiDeleteDialog from '@/components/client/MultiDeleteDialog.vue';
import MultiRestore from '@/components/client/MultiRestore.vue';
import Restore from '@/components/client/Restore.vue';
import ShowDialog from '@/components/client/ShowDialog.vue';
import Update from '@/components/client/Update.vue';
import UpdateDialog from '@/components/client/UpdateDialog.vue';
import Filter from '@/components/filters/user/Filter.vue';
import Icon from '@/components/Icon.vue';
import { FilterMatchMode } from '@primevue/core/api';
import { onMounted, onUpdated, ref } from 'vue';
import { usePhoneLink } from '@/composables/usePhoneLink';

const props = defineProps({
    entities: {
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
                multiRestore: false,
                multiDelete: false,
            };
        },
    },
    routes: {
        type: Object,
        default() {
            return {};
        },
    },
});

const emit = defineEmits(['count']);

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
const visible = ref(false);

onMounted(() => {
    items.value = props.entities.data;
    if (items.value.length > 0) {
        pagination.value = true;
    }
    loading.value = false;
});

onUpdated(() => {
    emit('count', (count.value = items.value.length));
    if (items.value.length == 0) {
        pagination.value = false;
    }
});

const onDeleteItem = (id: any) => {
    operationWithSingleItem(id);
};
const onLoadItem = () => {
    items.value = props.entities.data;
};

const onRestoreItem = (id: any) => {
    operationWithSingleItem(id);
};

const onRestoreSelectedItems = () => {
    operationWithSelectedItems();
};

const onDeleteSelectedItems = () => {
    operationWithSelectedItems();
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

const operationWithSelectedItems = () => {
    items.value = items.value.filter((val: any) => !selectedItems.value.includes(val));
    selectedItems.value = null;
};

const operationWithSingleItem = (id: any) => {
    items.value = items.value.filter((val: any) => val.id !== id);
};



const {getPhone}=usePhoneLink();
</script>

<template>
    <div class="grid auto-cols-fr">
        <Toolbar class="mb-6">
            <template #start>
                <div class="flex flex-row items-start space-x-2">
                    <span class="sm:hidden">
                        <Create
                            v-if="tools.create"
                            icon-name="UserRoundPlus"
                            label="New"
                            title="New client"
                            :route="routes.create"
                            @create-item="onLoadItem"
                        />
                    </span>
                    <span class="hidden sm:table-cell">
                        <CreateDialog
                            v-if="tools.create"
                            icon-name="UserRoundPlus"
                            label="New"
                            title="New client"
                            :route="routes.create"
                            @create-item="onLoadItem"
                        />
                    </span>
                    <span class="hidden sm:table-cell">
                        <MultiRestore
                            v-if="tools.restore"
                            :entity="selectedItems"
                            label="Восстановить"
                            icon-name="Undo2"
                            :route="routes.multiRestore"
                            @restore-items="onRestoreSelectedItems"
                            :disabled="!selectedItems || !selectedItems.length"
                        />
                    </span>
                    <span class="hidden sm:table-cell">
                        <MultiDeleteDialog
                            :entity="selectedItems"
                            icon-name=""
                            :route="routes.multiDestroy"
                            :disabled="!selectedItems || !selectedItems.length"
                            @delete-items="onDeleteSelectedItems"
                        />
                    </span>
                </div>
            </template>
            <template #end>
                <Drawer
                    v-model:visible="visible"
                    header="Filter"
                    :pt="{
                        header: {
                            class: '!py-[0.5rem]',
                        },
                        title: {
                            class: '!text-lg',
                        },
                    }"
                >
                    <p class="text-center text-gray-500">Фильтр для модели Client в разработке</p>
                    <Filter :entities="entities" class-name="grid items-end gap-5 mt-2 !hidden" />
                </Drawer>
                <Button icon="pi pi-search" @click="visible = true" size="small" />
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
            :globalFilterFields="['name', 'surname', 'middleName', 'phone', 'email', 'comment', 'created_at', 'total']"
            sortMode="multiple"
            removable-sort
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 15, 20, 25]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} items"
            :loading="loading"
        >
            <template #header>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h4 class="m-0">Manage clients</h4>
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" name="search" class="h-[28px]" placeholder="Search..." size="small" />
                    </IconField>
                </div>
            </template>
            <template #empty><p class="text-center text-xl font-bold">No entities</p></template>
            <template #loading> Loading items data. Please wait.</template>
            <Column
                selectionMode="multiple"
                :exportable="false"
                :pt="{
                    root: {
                        class: 'hidden sm:table-cell',
                    },
                    pcRowCheckbox: {
                        input: {
                            name: 'selectedItem',
                        },
                    },
                    pcHeaderCheckbox: {
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
                    <img v-if="slotProps.data.avatar" :src="slotProps.data.avatar" :alt="slotProps.data.avatar" class="max-w-[48px] rounded" />
                    <img v-else class="max-w-[48px] rounded" src="../../../../public/no_avatar_big.png" alt="" />
                </template>
            </Column>
            <Column field="name" header="Name" :sortable="true">
                <template #body="slotProps">
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white">
                        {{ slotProps.data.name }}
                        {{ slotProps.data.middleName }}
                        {{ slotProps.data.surname }}
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
                                <Icon name="Settings" />
                            </template>
                            <template #item>
                                <Restore
                                    v-if="tools.restore"
                                    :id="slotProps.data.id"
                                    icon-name="Undo2"
                                    :route="routes.restore"
                                    @restore-customer="onRestoreItem"
                                />
                                <Update
                                    v-if="tools.update"
                                    :entity="slotProps.data"
                                    icon-name="UserPen"
                                    label=""
                                    :route="routes.update"
                                    @update-item="onLoadItem"
                                />
                                <DeleteDialog
                                    v-if="tools.remove"
                                    :entity="slotProps.data"
                                    icon-name="UserMinus"
                                    :route="routes.delete"
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
                    <span class="flex flex-row items-start justify-start">
                        <UpdateDialog
                            :key="slotProps.data.id"
                            v-if="tools.update"
                            :entity="slotProps.data"
                            icon-name="UserPen"
                            label="Edit item"
                            :route="routes.update"
                            @update-item="onLoadItem"
                        />
                        <ShowDialog
                            :key="slotProps.data.id"
                            :entity="slotProps.data"
                            icon-name="UserSearch"
                            label="Show item"
                            :route="routes.show"
                            @update-item="onLoadItem"
                        />
                        <Restore
                            v-if="tools.restore"
                            :id="slotProps.data.id"
                            icon-name="Undo2"
                            :route="routes.restore"
                            @restore-customer="onRestoreItem"
                        />
                        <DeleteDialog
                            v-if="tools.remove"
                            :entity="slotProps.data"
                            icon-name="UserMinus"
                            :route="routes.delete"
                            @delete-item="onDeleteItem"
                        />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
