<script setup lang="ts">
import ClientRestore from '@/components/client/ClientRestore.vue';
import CreateDialog from '@/components/client/CreateDialog.vue';
import DeleteDialog from '@/components/client/DeleteDialog.vue';
import MultiDeleteDialog from '@/components/client/MultiDeleteDialog.vue';
import MultiRestore from '@/components/client/MultiRestore.vue';
import UpdateDialog from '@/components/client/UpdateDialog.vue';
import { FilterMatchMode } from '@primevue/core/api';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import Toolbar from 'primevue/toolbar';
import { onBeforeUpdate, onMounted, ref } from 'vue';

const props = defineProps({
    entities: {
        type: Object,
        required: true,
        default() {
            return {};
        },
    },
    columns: {
        type: Object,
    },
    filtersFields: {
        type: Object,
    },
    tools: {
        type: Boolean,
        default: false,
    },
    create: {
        type: Boolean,
        default: false,
    },
    restore: {
        type: Boolean,
        default: false,
    },
    update: {
        type: Boolean,
        default: false,
    },
    remove: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['count']);

const dt = ref();
const items = ref();
const count = ref();
const item = ref({});
const selectedItems = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const fields = ref([]);

const loading = ref(true);
const pagination = ref(false);

onMounted(() => {
    items.value = props.entities.data;
    if (items.value.length > 0) {
        pagination.value = true;
    }
    loading.value = false;

    Object.entries(props.filtersFields).forEach(([key1, value1]) => {
        Object.entries(value1).forEach(([key2, value2]) => {
            if (key2 === 'field') {
                fields.value.push(value2);
            }
        });
    });
    console.log(fields.value);
console.log(props.filtersFields);
});

onBeforeUpdate(() => {
    emit('count', (count.value = items.value.length));
    if (items.value.length == 0) {
        pagination.value = false;
    }
});
const onAddItem = (data: object) => {
    items.value.push(data);
    items.value = props.entities.data;
    item.value = {};
};
const onUpdateItem = (data: object) => {
    item.value = { ...data };
    if (item.value.id) {
        items.value[findIndexById(item.value.id)] = item.value;
        item.value = {};
    }
};

const onRestoreItem = (id: any) => {
    operationWithSingleItem(id);
};

const onRestoreSelectedItems = () => {
    operationWithSelectedItems();
};

const onDeleteItem = (id: any) => {
    operationWithSingleItem(id);
};
const findIndexById = (id: any) => {
    let index = -1;
    for (let i = 0; i < items.value.length; i++) {
        if (items.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
};

const onDeleteSelectedItems = () => {
    operationWithSelectedItems();
};

const getStatusLabel = (status: any) => {
    switch (status) {
        case true:
            return 'success';
        case false:
            return 'warn';
        default:
            return null;
    }
};

const operationWithSelectedItems = () => {
    items.value = items.value.filter((val: any) => !selectedItems.value.includes(val));
    selectedItems.value = null;
};

const operationWithSingleItem = (id: any) => {
    items.value = items.value.filter((val: any) => val.id !== id);
};


</script>
<!--:globalFilterFields="['id', 'name', 'surname', 'middleName', 'phone', 'email', 'comment', 'discount', 'source']"-->

<template>
    <div class="grid auto-cols-fr">
        <Toolbar class="mb-6">
            <template #start>
                <div class="flex flex-col items-center space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                    <CreateDialog
                        v-if="create"
                        @add-item="onAddItem"
                        icon-name="UserRoundPlus"
                        label="Новый"
                        title="Новый клиент"
                        url-to-update="clients.store"
                    />
                    <MultiRestore
                        v-if="restore"
                        :entity="selectedItems"
                        label="Восстановить"
                        icon-name="Undo2"
                        url-to-restore="multiRestore"
                        @restore-items="onRestoreSelectedItems"
                        :disabled="!selectedItems || !selectedItems.length"
                    />
                    <MultiDeleteDialog
                        :entity="selectedItems"
                        icon-name=""
                        url-to-delete="multiDestroy"
                        :disabled="!selectedItems || !selectedItems.length"
                        @delete-items="onDeleteSelectedItems"
                    />
                </div>
            </template>
        </Toolbar>
        <DataTable
            class="text-[15px]"
            ref="dt"
            v-model:selection="selectedItems"
            :value="items"
            dataKey="id"
            :paginator="pagination"
            :rows="10"
            :filters="filters"
            :globalFilterFields="fields"
            sort
            removable-sort
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 15, 20, 25]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} items"
            :loading="loading"
        >
            <template #header>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h4 class="m-0">Manage entities</h4>
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
                style="width: 3rem"
                :exportable="false"
                :pt="{
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
            <Column v-for="col of columns" :key="col.field" :field="col.field" :header="col.header" :sortable="true">
                <template v-if="col.field === 'avatar' || col.field === 'photo' || col.field === 'image'" #body="slotProps">
                    <img v-if="slotProps.data.avatar" :src="slotProps.data.avatar" :alt="slotProps.data.avatar" class="rounded" style="width: 64px" />
                    <img v-else class="rounded" style="width: 64px" src="../../../../../public/no_avatar_big.png" alt="" />
                </template>
                <template v-else-if="col.field === 'name'" #body="slotProps">
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white">
                        <p>
                            {{ slotProps.data.name }}
                            {{ slotProps.data.middleName }}
                            {{ slotProps.data.surname }}
                        </p>
                    </div>
                    <small class="text-xs font-normal text-gray-900 dark:text-gray-300">ID: {{ slotProps.data.id }}</small>
                </template>
                <template v-else-if="col.field === 'blacklist'" #body="slotProps">
                    <Tag :value="slotProps.data.blacklist" :severity="getStatusLabel(slotProps.data.blacklist)" />
                </template>
                <template v-else-if="col.field === 'prepayment'" #body="slotProps">
                    <Tag :value="slotProps.data.prepayment" :severity="getStatusLabel(slotProps.data.prepayment)" />
                </template>
            </Column>
            <Column :exportable="false" header="Tools" style="min-width: 8rem" v-if="tools">
                <template #body="slotProps">
                    <span class="flex flex-row items-center justify-center">
                        <ClientRestore
                            v-if="restore"
                            :id="slotProps.data.id"
                            icon-name="Undo2"
                            url-to-restore="clients.restore"
                            @restore-customer="onRestoreItem"
                        />
                        <UpdateDialog
                            v-if="update"
                            :entity="slotProps.data"
                            icon-name="Edit2"
                            label="Edit item"
                            url-to-update="entities.update"
                            @update-item="onUpdateItem"
                        />
                        <DeleteDialog v-if="remove" :entity="slotProps.data" icon-name="Trash2" @delete-item="onDeleteItem" />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
