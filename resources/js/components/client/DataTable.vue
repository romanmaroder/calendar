<script setup lang="ts">
import CreateDialog from '@/components/client/CreateDialog.vue';
import DeleteDialog from '@/components/client/DeleteDialog.vue';
import MultiDeleteDialog from '@/components/client/MultiDeleteDialog.vue';
import MultiRestore from '@/components/client/MultiRestore.vue';
import Restore from '@/components/client/Restore.vue';
import UpdateDialog from '@/components/client/UpdateDialog.vue';
//import UpdateDialog from '../../pages/client/Edit.vue';
import { FilterMatchMode } from '@primevue/core/api';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Toolbar from 'primevue/toolbar';
import { onBeforeUpdate, onMounted, ref } from 'vue';

const props = defineProps({
    entities: {
        type: Object,
        required: true,
        default() {
            return {};
        }
    },
    columns: {
        type: Object
    },
    filtersFields: {
        type: Object
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
                multiDelete: false
            };
        }
    },
    routes: {
        type: Object,
        default() {
            return {};
        }
    },
    errors: {
        type: Object,
        default() {
            return {};
        }
    }
});

const emit = defineEmits(['count']);

const dt = ref();
const items = ref();
const count = ref();
const selectedItems = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
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

    filterFields();
});

onBeforeUpdate(() => {
    items.value = props.entities.data;
    emit('count', (count.value = items.value.length));
    if (items.value.length == 0) {
        pagination.value = false;
    }
});

const onRestoreItem = (id: any) => {
    operationWithSingleItem(id);
};

const onRestoreSelectedItems = () => {
    operationWithSelectedItems();
};

const onDeleteItem = (id: any) => {
    operationWithSingleItem(id);
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
};

const operationWithSelectedItems = () => {
    items.value = items.value.filter((val: any) => !selectedItems.value.includes(val));
    selectedItems.value = null;
};

const operationWithSingleItem = (id: any) => {
    items.value = items.value.filter((val: any) => val.id !== id);
};

const trimPhone=(phoneNumber: string)=>
{
    return phoneNumber.replace(/[- )(]/g,'');
}
</script>

<template>
    <div class="grid auto-cols-fr">
        <Toolbar class="mb-6">
            <template #start>
                <div class="flex flex-col items-center space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                    <CreateDialog v-if="tools.create" icon-name="UserRoundPlus" label="New" title="New client" :route="routes.create" />
                    <MultiRestore
                        v-if="tools.restore"
                        :entity="selectedItems"
                        label="Restore"
                        icon-name="Undo2"
                        :route="routes.multiRestore"
                        @restore-items="onRestoreSelectedItems"
                        :disabled="!selectedItems || !selectedItems.length"
                    />
                    <MultiDeleteDialog
                        :entity="selectedItems"
                        icon-name=""
                        :route="routes.multiDestroy"
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
            sortMode="multiple"
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
                    <img v-else class="rounded" style="width: 64px" src="../../../../public/no_avatar_big.png" alt="" />
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
                <template v-else-if="col.field === 'phone'" #body="slotProps">
                    <Button
                        as="a"
                        variant="link"
                        :label="slotProps.data.phone"
                        :href="'tel:' + trimPhone(slotProps.data.phone)"
                        rel="noopener"
                    />
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
                        <Restore
                            v-if="tools.restore"
                            :id="slotProps.data.id"
                            icon-name="Undo2"
                            :route="routes.restore"
                            @restore-customer="onRestoreItem"
                        />
                        <UpdateDialog
                            :key="slotProps.data.id"
                            v-if="tools.update"
                            :entity="slotProps.data"
                            icon-name="Edit2"
                            label="Edit item"
                            :route="routes.update"
                            :errors="props.errors"
                        />
                        <DeleteDialog
                            v-if="tools.remove"
                            :entity="slotProps.data"
                            icon-name="Trash2"
                            :route="routes.delete"
                            @delete-item="onDeleteItem"
                        />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
