<script setup lang="ts">
import ClientCreateDialog from '@/components/client/CreateDialog.vue';
import ClientDeleteDialog from '@/components/client/DeleteDialog.vue';
import ClientMultiDeleteDialog from '@/components/client/MultiDeleteDialog.vue';
import ClientUpdateDialog from '@/components/client/UpdateDialog.vue';
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
    clients: {
        type: Object,
        required: true,
    },
    count: {
        type: Number,
    },
    columns: {
    }
});


const dt = ref();
const customers = ref();
const customer = ref({});
const selectedCustomers = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const loading = ref(true);
const pagination = ref(false)

onMounted(() => {
    customers.value = props.clients.data;
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
const onAddCustomer = (data: object) => {
    customers.value.push(data);
};
const onUpdateCustomer = (data: object) => {
    customer.value = { ...data };
    if (customer.value.id) {
        customers.value[findIndexById(customer.value.id)] = customer.value;
        customer.value = {};
    }
};
const onDeleteCustomer = (id: any) => {
    customers.value = customers.value.filter((val: any) => val.id !== id);
};
const findIndexById = (id: any) => {
    let index = -1;
    for (let i = 0; i < customers.value.length; i++) {
        if (customers.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
};

const onDeleteSelectedCustomers = () => {
    customers.value = customers.value.filter((val:any) => !selectedCustomers.value.includes(val));
    selectedCustomers.value = null;
};

const getStatusLabel = (status:any) => {
    switch (status) {
        case true:
            return 'success';

        case false:
            return 'warn';

        default:
            return null;
    }
};
</script>

<template>
    <div class="grid auto-cols-fr">
        <Toolbar class="mb-6">
            <template #start>
                <div class="flex flex-col items-center space-y-2 sm:flex-row sm:space-x-2 sm:space-y-0">
                    <ClientCreateDialog
                        @add-customer="onAddCustomer"
                        icon-name="UserRoundPlus"
                        label="Новый"
                        title="Новый клиент"
                        url-to-update="clients.store"
                    />
                    <ClientMultiDeleteDialog
                        :client="selectedCustomers"
                        icon-name=""
                        url-to-delete="multiDestroy"
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
            :rows="10"
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
                        <InputText v-model="filters['global'].value" placeholder="Search..." size="small" />
                    </IconField>
                </div>
            </template>
            <template #empty><p class="text-center text-xl font-bold">No Clients</p></template>
            <template #loading> Loading customers data. Please wait. </template>
            <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
            <Column v-for="col of columns" :key="col.field" :field="col.field" :header="col.header">
                <template v-if="col.field === 'avatar' || col.field ==='photo' || col.field ==='image'" #body="slotProps">
                    <img v-if="slotProps.data.avatar" :src="slotProps.data.avatar" :alt="slotProps.data.avatar" class="rounded" style="width: 64px" />
                    <img v-else class="rounded" style="width: 64px" src="../../../../public/no_avatar_big.png" alt="" />
                </template>
                <template v-else-if="col.field ==='name'" #body="slotProps">
                    <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white">
                        <p>
                            {{ slotProps.data.name }}
                            {{ slotProps.data.middleName }}
                            {{ slotProps.data.surname }}
                        </p>
                    </div>
                    <small class="text-xs font-normal text-gray-900 dark:text-gray-300">ID: {{ slotProps.data.id }}</small>
                </template>
                <template v-else-if="col.field ==='blacklist'" #body="slotProps">
                    <Tag :value="slotProps.data.blacklist" :severity="getStatusLabel(slotProps.data.blacklist)" />
                </template>
                <template  v-else-if="col.field ==='prepayment'" #body="slotProps">
                    <Tag :value="slotProps.data.prepayment" :severity="getStatusLabel(slotProps.data.prepayment)" />
                </template>
            </Column>
<!--            <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
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
            <Column field="created_at" header="Created" :sortable=true style="min-width: 10rem"></Column>
            <Column field="blacklist" header="Blacklist" :sortable=true style="min-width: 5rem">
                <template #body="slotProps">
                    <Tag :value="slotProps.data.blacklist" :severity="getStatusLabel(slotProps.data.blacklist)" />
                </template>
            </Column>
            <Column field="prepayment" header="Prepayment" :sortable=true style="min-width: 5rem">
                <template #body="slotProps">
                    <Tag :value="slotProps.data.prepayment" :severity="getStatusLabel(slotProps.data.prepayment)" />
                </template>
            </Column>
            <Column field="discount" header="Discount" :sortable=true style="min-width: 5rem"></Column>
            <Column field="records" header="Records" :sortable=true style="min-width: 5rem"></Column>
            <Column field="total" header="Total" :sortable=true style="min-width: 5rem"></Column>-->
            <Column :exportable="false" header="Tools" style="min-width: 8rem">
                <template #body="slotProps">
                    <span class="flex flex-row items-center justify-center">
                        <ClientUpdateDialog
                            :client="slotProps.data"
                            icon-name="Edit2"
                            label="Edit Customer"
                            url-to-update="clients.update"
                            @update-customer="onUpdateCustomer"
                        ></ClientUpdateDialog>
                        <ClientDeleteDialog :client="slotProps.data" icon-name="Trash2"
                                            @delete-customer="onDeleteCustomer" />
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
