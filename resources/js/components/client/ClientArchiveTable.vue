<script setup lang="ts">
import ClientDeleteDialog from '@/components/client/ClientDeleteDialog.vue';
import ClientMultiDeleteDialog from '@/components/client/ClientMultiDeleteDialog.vue';
import ClientMultiRestore from '@/components/client/ClientMultiRestore.vue';
import ClientRestore from '@/components/client/ClientRestore.vue';
import { FilterMatchMode } from '@primevue/core/api';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Toolbar from 'primevue/toolbar';
import { onBeforeUpdate, onMounted, ref } from 'vue';

const props = defineProps({
    clients: {
        type: Object,
        required: true,
    },
    count: {
        type: Number,
    },
});

const dt = ref();
const customers = ref();
const selectedCustomers = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const loading = ref(true);
const pagination = ref(false);

onMounted(() => {
    customers.value = props.clients.data
    if (customers.value.length > 0) {
        pagination.value = true;
    }
    loading.value = false;
});

onBeforeUpdate(()=>{
    if (customers.value.length == 0) {
        pagination.value = false;
    }
});
const onDeleteCustomer = (id: any) => {
    operationWithSingleCustomer(id);
};

const onRestoreCustomer = (id: any) => {
    operationWithSingleCustomer(id);
};

const onRestoreSelectedCustomers = () => {
    operationWithSelectedCustomers();
};
const onDeleteSelectedCustomers = () => {
    operationWithSelectedCustomers();
};
const operationWithSelectedCustomers = () => {
    customers.value = customers.value.filter((val: any) => !selectedCustomers.value.includes(val));
    selectedCustomers.value = null;
};

const operationWithSingleCustomer = (id: any) => {
    customers.value = customers.value.filter((val: any) => val.id !== id);
};

</script>

<template>
    <div class="grid auto-cols-fr">
        <Toolbar class="mb-6">
            <template #start>
                <div class="flex flex-col items-center space-y-2 sm:flex-row sm:space-x-2 sm:space-y-0">
                    <ClientMultiRestore
                        :client="selectedCustomers"
                        label="Восстановить"
                        icon-name="Undo2"
                        url-to-restore="multiRestore"
                        @restore-customers="onRestoreSelectedCustomers"
                        :disabled="!selectedCustomers || !selectedCustomers.length"
                    />
                    <ClientMultiDeleteDialog
                        :client="selectedCustomers"
                        icon-name=""
                        url-to-delete="trash"
                        :disabled="!selectedCustomers || !selectedCustomers.length"
                        @delete-customers="onDeleteSelectedCustomers"
                    />
                </div>
            </template>
        </Toolbar>
        <DataTable
            class="text-[15px]"
            ref="dt"
            v-model:selection="selectedCustomers"
            :value="customers"
            dataKey="id"
            :paginator="pagination"
            :rows="5"
            :filters="filters"
            removable-sort
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 15, 20, 25]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} customers"
            :loading="loading"
        >
            <template #header>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h4 class="m-0">Manage Clients</h4>
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Search..." />
                    </IconField>
                </div>
            </template>
            <template #empty><p class="text-center text-xl font-bold">The basket is empty</p></template>
            <template #loading>Loading basket data. Please wait.</template>
            <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
            <Column header="Avatar" frozen alignFrozen="right">
                <template #body="slotProps">
                    <img v-if="slotProps.data.avatar" :src="slotProps.data.avatar" :alt="slotProps.data.avatar" class="rounded" style="width: 64px" />
                    <img v-else class="rounded" style="width: 64px" src="../../../../public/no_avatar_big.png" alt="" />
                </template>
            </Column>
            <Column field="name" header="Name" :sortable=true style="min-width: 12rem">
                <template #body="slotProps">
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white">
                        <p>
                            {{ slotProps.data.name }}
                            {{ slotProps.data.middleName }}
                            {{ slotProps.data.surname }}
                        </p>
                    </div>
                    <small class="text-xs font-normal text-gray-900 dark:text-gray-300">ID: {{ slotProps.data.id }}</small>
                </template>
            </Column>
            <Column field="surname" header="Surname" :sortable=true class="hidden"></Column>
            <Column field="middleName" header="MiddleName" :sortable=true class="hidden"></Column>
            <Column field="phone" header="Phone" :sortable=true style="min-width: 10rem"></Column>
            <Column field="email" header="Email" :sortable=true style="min-width: 10rem"></Column>
            <Column field="comment" header="Comment" :sortable=true style="min-width: 12rem"></Column>
            <Column field="deleted_at" header="Deleted" :sortable=true style="min-width: 10rem"></Column>
            <Column :exportable="false" header="Tools" style="min-width: 8rem" >
                <template #body="slotProps">
                    <span class="flex flex-row items-center justify-center">
                        <ClientRestore
                            :id="slotProps.data.id"
                            icon-name="Undo2"
                            url-to-restore="clients.restore"
                            @restore-customer="onRestoreCustomer"
                        />
                        <ClientDeleteDialog :client="slotProps.data"
                                            icon-name="Trash2"
                                            url-to-delete="trash"
                                            @delete-customer="onDeleteCustomer" />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
