<script setup lang="ts">
import Filter from '@/components/filters/user/Filter.vue';
import Icon from '@/components/Icon.vue';
import DeleteDialog from '@/components/user/DeleteDialog.vue';
import FormDrawer from '@/components/user/FormDrawer.vue';
import MultiDeleteDialog from '@/components/user/MultiDeleteDialog.vue';
import MultiRestore from '@/components/user/MultiRestore.vue';
import Restore from '@/components/user/Restore.vue';
import Show from '@/components/user/Show.vue';
import ShowDialog from '@/components/user/ShowDialog.vue';
import UpdateDialog from '@/components/user/UpdateDialog.vue';
import { getFullname } from '@/composables/useFullname';
import { getInitials } from '@/composables/useInitials';
import { usePhoneLink } from '@/composables/usePhoneLink';
import { width } from '@/composables/useVisible';
import { workingWithTableItems } from '@/composables/workingWithTableItems';
import { FilterMatchMode } from '@primevue/core/api';
import {  onMounted, provide, readonly, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import { User } from '@/types';

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

const formRoutesProps: object = ref(props.routes);
provide('formRoutesProps', readonly(formRoutesProps));

const dt = ref();
const items = ref();
const count = ref();
const selectedItems = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const loading = ref(true);
const pagination = ref(false);
const visible = ref(false);
const { size } = width(640);

const { getPhone } = usePhoneLink();
const {useRows } = workingWithTableItems();


onMounted(() => {
    items.value = props.entities.data;
    loading.value = false;
});

watch(items, () => {
    pagination.value = items.value.length > 0;
    count.value = items.value.length;
});

watch(count, () => {
    emit('count', count);
});


const onDeleteItem = (id: User) => {
    useRows(items, ref([id]));
};
const onLoadItem = () => {
    items.value = props.entities.data;
};

const onRestoreItem = (id: User) => {
    useRows(items, ref([id]));
};

const onRestoreSelectedItems = () => {
    useRows(items, selectedItems);
};

const onDeleteSelectedItems = () => {
    useRows(items, selectedItems);
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
        <Toolbar class="mb-6">
            <template #start>
                <div class="flex flex-row items-start space-x-2">
                    <span class="">
                        <form-drawer v-if="tools.create && size" @new-user="onLoadItem" icon-name="pi pi-user-plus" label="New" title="New user" />
                        <Button
                            v-if="tools.create && !size"
                            as="a"
                            icon="pi pi-user-plus"
                            label="New"
                            :href="route('users.create')"
                            size="small"
                            class="mx-2"
                        />
                    </span>
                    <span class="hidden sm:flex">
                        <multi-restore
                            v-if="tools.restore"
                            :entity="selectedItems"
                            label="Восстановить"
                            icon-name="Undo2"
                            :route="routes.user.uri.multiRestore"
                            @restore-items="onRestoreSelectedItems"
                            :disabled="!selectedItems || !selectedItems.length"
                        />
                    </span>
                    <span class="hidden sm:flex">
                        <multi-delete-dialog
                            :entity="selectedItems"
                            icon-name=""
                            :route="routes.user.uri.multiDestroy"
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
                    <p class="text-center text-gray-500">Фильтр для модели User в разработке</p>
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
            :globalFilterFields="['name', 'surname', 'middleName', 'phone', 'email', 'comment', 'created_at', 'birthday']"
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
                    <Avatar
                        v-if="!slotProps.data.avatar"
                        size="large"
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
                                class: 'max-w-[48px] max-h-[48px] rounded-md',
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
                    <p class="sm:hidden">
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.branch.name }}</small>
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
                                <restore
                                    v-if="tools.restore"
                                    :id="slotProps.data"
                                    icon-name="Undo2"
                                    :route="routes.user.uri.restore"
                                    @restore-customer="onRestoreItem"
                                />
                                <form-drawer
                                    v-if="tools.update"
                                    icon-name="pi pi-user-edit"
                                    label=""
                                    :entity="slotProps.data"
                                    @update-user="onLoadItem"
                                    variant="link"
                                />
                                <Show :entity="slotProps.data" icon-name="pi pi-user" label="" route="" />
                                <delete-dialog
                                    v-if="tools.remove"
                                    :entity="slotProps.data"
                                    icon-name="pi pi-user-minus"
                                    :route="routes.user.uri.destroy"
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
                    <p class="">
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.branch.name }}</small>
                    </p>
                    <p class="2xl:hidden">
                        <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.email }}</small>
                    </p>
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
                field="birthday"
                header="Birthday"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'hidden xl:table-cell',
                    },
                }"
            >
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
                        <update-dialog
                            :key="slotProps.data.id"
                            v-if="tools.update"
                            :entity="slotProps.data"
                            icon-name="UserPen"
                            label="Edit item"
                            :route="routes.user.uri.update"
                            @update-item="onLoadItem"
                        />
                        <ShowDialog :key="slotProps.data.id" :entity="slotProps.data" icon-name="UserSearch" label="Show item" route="" />
                        <restore
                            v-if="tools.restore"
                            :id="slotProps.data.id"
                            icon-name="Undo2"
                            :route="routes.user.uri.restore"
                            @restore-customer="onRestoreItem"
                        />
                        <delete-dialog
                            v-if="tools.remove"
                            :entity="slotProps.data"
                            icon-name="UserMinus"
                            :route="routes.user.uri.destroy"
                            @delete-item="onDeleteItem"
                        />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
