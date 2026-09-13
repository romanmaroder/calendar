<script setup lang="ts">
import FormDrawer from '@/components/client/FormDrawer.vue';
import Show from '@/components/client/Show.vue';
import DeleteConfirmation from '@/components/common/DeleteConfirmation.vue';
import Restore from '@/components/common/Restore.vue';
import { getFullname } from '@/composables/useFullname';
import { getInitials } from '@/composables/useInitials';
import { usePhoneLink } from '@/composables/utils/phone/usePhoneLink';
import { workingWithTableItems } from '@/composables/workingWithTableItems';
import { Client } from '@/types';
import { ClientTranslations } from '@/types/translations';
import { router, usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { useMediaQuery } from '@vueuse/core';
import { computed, inject, onBeforeMount, PropType, ref, watch } from 'vue';
import { route } from 'ziggy-js';

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

const translations = inject<ClientTranslations>('translations');

const page = usePage();

const hasPermission = (permission: string) => {
    const userPermissions = page.props.auth?.user?.permissions ?? [];
    return userPermissions.includes(permission);
};

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
    emit('count', count.value);
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
                end: 'w-full mt-3 sm:w-auto sm:mt-0',
            }"
        >
            <template #start>
                <div class="flex flex-row items-start">
                    <span class="">
                        <form-drawer
                            v-if="tools.create && !isLargeScreen && hasPermission('clients.create')"
                            @new-user="onLoadItem"
                            icon-name="pi pi-user-plus"
                            raised
                            :label="translations?.button?.create"
                            title="New client"
                        />
                        <Button
                            v-if="tools.create && isLargeScreen && hasPermission('clients.create')"
                            icon="pi pi-user-plus"
                            :label="translations?.button?.create"
                            raised
                            size="small"
                            class="mx-2"
                            @click="router.visit(route('clients.create'))"
                        />
                    </span>
                    <span class="hidden space-x-2 sm:flex">
                        <restore
                            v-if="tools.restore && hasPermission('clients.restore')"
                            :entity="selectedItems"
                            :label="translations?.button?.restore"
                            icon-name="pi pi-replay"
                            type="multi"
                            route="clients.bulk.restore"
                            :disabled="!selectedItems || !selectedItems.length"
                            @restore-items="onRestoreSelectedItems"
                        />
                        <delete-confirmation
                            v-if="hasPermission('clients.delete')"
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
                <div class="flex w-full space-x-2">
                    <IconField class="w-full rounded-md shadow-sm sm:w-auto">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText
                            v-model="filters['global'].value"
                            name="search"
                            class="w-full sm:w-auto"
                            :placeholder="translations?.toolbar.search"
                            size="small"
                        />
                    </IconField>
                    <Button
                        v-if="page.url === '/clients' && hasPermission('clients.delete')"
                        icon="pi pi-box"
                        size="small"
                        severity="warn"
                        raised
                        variant="text"
                        v-tooltip.bottom="translations?.label.archive"
                        :label="translations?.toolbar.archive"
                        @click="router.visit(route('clients.archive'))"
                    />
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
            paginatorTemplate="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink  RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10]"
            currentPageReportTemplate="{first} - {last} / {totalRecords}"
            :loading="loading"
        >
            <template #empty
                ><p class="text-center text-xl font-bold">{{ translations?.table?.empty_text }}</p>
            </template>
            <template #loading>{{ translations?.table?.loading }}</template>
            <Column
                v-if="hasPermission('clients.delete')"
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
                :header="translations?.table.avatar"
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
            <Column field="name" :header="translations?.table?.name" :sortable="true">
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
                :header="translations?.table.info"
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
                                    route="clients.restore"
                                    @restore-item="onRestoreItem"
                                />
                                <form-drawer
                                    v-if="tools.update && hasPermission('clients.edit')"
                                    icon-name="pi pi-user-edit"
                                    label=""
                                    :entity="slotProps.data"
                                    @update-user="onLoadItem"
                                    variant="link"
                                />
                                <show :entity="slotProps.data" icon-name="pi pi-user" label="" route="" />
                                <delete-confirmation
                                    v-if="tools.remove && hasPermission('clients.delete')"
                                    :entity="slotProps.data"
                                    icon-name="pi pi-user-minus"
                                    :route="isDeleted ? 'clients.force' : 'clients.soft.delete'"
                                    @delete-item="onDeleteItem"
                                />
                            </template>
                        </SpeedDial>
                    </div>
                </template>
            </Column>
            <Column
                field="phone"
                :header="translations?.table?.phone_email"
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
                :header="translations?.table?.discount"
                :sortable="true"
                :pt="{
                    root: {
                        class: 'hidden lg:table-cell',
                    },
                }"
            ></Column>
            <Column
                field="total"
                :header="translations?.table?.total"
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
                :header="translations?.table?.email"
                :pt="{
                    root: {
                        class: 'hidden 2xl:table-cell',
                    },
                }"
            ></Column>
            <Column
                field="blacklist"
                :header="translations?.table?.blacklist"
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
                :header="translations?.table?.prepayment"
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
                :header="translations?.table?.comment"
                :pt="{
                    root: {
                        class: 'hidden lg:table-cell max-w-[250px] lg:truncate',
                    },
                }"
            ></Column>
            <Column
                field="source"
                :header="translations?.table?.source"
                :pt="{
                    root: {
                        class: 'hidden 2xl:table-cell',
                    },
                }"
            ></Column>
            <Column
                :exportable="false"
                :header="translations?.table?.actions"
                :pt="{
                    root: {
                        class: 'hidden sm:table-cell',
                    },
                }"
            >
                <template #body="slotProps">
                    <span class="flex flex-row flex-wrap items-start justify-start">
                        <Button
                            v-if="tools.update && hasPermission('clients.edit')"
                            variant="link"
                            icon="pi pi-user-edit"
                            label=""
                            size="small"
                            @click="router.visit(route('clients.edit', slotProps.data))"
                            :pt="{
                                icon: {
                                    class: 'mx-1 text-sky-600 hover:text-sky-900 focus:text-sky-900',
                                },
                            }"
                        />
                        <Button
                            variant="link"
                            icon="pi pi-user"
                            label=""
                            size="small"
                            @click="router.visit(route('clients.show', slotProps.data))"
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
                            v-if="tools.remove && hasPermission('clients.delete')"
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
