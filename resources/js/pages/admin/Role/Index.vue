<script setup lang="ts">
import { useDateField } from '@/composables/utils/useDateField';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Roles', href: '/admin/roles' }];

defineProps<{
    roles: Array<{ id: number; name: string; permissions: Array<{ name: string }> }>;
    pagination: { current_page: number; last_page: number; total: number };
}>();

const deleteConfirmationVisible = ref(false);
const deleteEntity = ref<{ id: number; name: string } | null>(null);

const openDeleteModal = (entity: { id: number; name: string }) => {
    deleteEntity.value = entity;
    deleteConfirmationVisible.value = true;
};

const closeDeleteModal = () => {
    deleteConfirmationVisible.value = false;
    deleteEntity.value = null;
};

const handleDeleteConfirmed = () => {
    if (!deleteEntity.value) {
        closeDeleteModal();
        return;
    }

    const { id, name } = deleteEntity.value;

    router.delete(route('admin.roles.destroy', { role: id }), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Удалено',
                detail: `Роль "${name}" успешно удалена`,
                life: 3000,
            });
            closeDeleteModal();
            // Inertia автоматически перезагрузит страницу и обновит таблицу
        },
        onError: (errors) => {
            console.error(errors);
            toast.add({
                severity: 'error',
                summary: 'Ошибка',
                detail: 'Не удалось удалить роль',
                life: 3000,
            });
            closeDeleteModal();
        },
    });
};

const toast = useToast();

const goToCreate = () => {
    router.visit(route('admin.roles.create'));
};

const goToEdit = (id: number) => {
    router.visit(route('admin.roles.edit', { role: id }));
};

</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
    <Head title="Roles" />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-cols-fr">
                <Toolbar class="mb-4">
                    <template #end>
                        <div class="flex flex-row items-end">
                            <Button as="a" icon="pi pi-plus" label="New Role" raised @click="goToCreate" size="small" class="mx-2" />
                        </div>
                    </template>
                </Toolbar>

                <DataTable
                    class=""
                    :value="roles"
                    :paginator="true"
                    :rows="20"
                    :total-records="pagination.total"
                    :current-page="pagination.current_page"
                    sort-mode="single"
                    responsive-layout="scroll"
                    dataKey="id"
                >
                    <Column
                        field="id"
                        header="ID"
                        :pt="{
                            root: {
                                class: 'hidden sm:table-cell',
                            },
                        }"
                    />
                    <Column field="name" header="Название" />
                    <Column
                        header="Разрешения"
                        :pt="{
                            root: {
                                class: 'hidden sm:table-cell',
                            },
                        }"
                    >
                        <template #body="slotProps">
                            <div class="space-x-1 space-y-1">
                                <Tag v-for="perm in slotProps.data?.permissions" :key="perm.id" severity="info">
                                    {{ perm.name }}
                                </Tag>
                            </div>
                        </template>
                    </Column>
                    <Column
                        header="Действия"
                        :pt="{
                            root: {
                                class: 'hidden sm:table-cell',
                            },
                        }"
                    >
                        <template #body="slotProps">
                            <div class="flex gap-2">
                                <Button
                                    icon="pi pi-pencil"
                                    rounded
                                    @click="goToEdit(slotProps.data.id)"
                                    severity="secondary"
                                    tooltip="Редактировать"
                                    variant="text"
                                />
                                <Button
                                    icon="pi pi-trash"
                                    rounded
                                    tooltip="'Delete'"
                                    @click="openDeleteModal(slotProps.data)"
                                    severity="danger"
                                    variant="text"
                                />
                            </div>
                        </template>
                    </Column>
                    <Column
                        field="name"
                        header="Действия"
                        :sortable="true"
                        :pt="{
                            root: {
                                class: 'sm:hidden',
                            },
                        }"
                    >
                        <template #body="slotProps">
                            <div class="text-sm font-medium text-wrap text-gray-900 dark:text-white"></div>
                            <small class="text-xs font-normal text-gray-900 dark:text-gray-300">{{ slotProps.data.email }} </small>
                            <p class="sm:hidden">
                                <small class="text-xs font-normal text-gray-900 dark:text-gray-300">
                                    {{
                                        useDateField({
                                            initialValue: slotProps.data.created_at,
                                            format: 'local',
                                        }).getPayloadValue()
                                    }}
                                </small>
                            </p>
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
                                        <Button
                                            icon="pi pi-pencil"
                                            rounded
                                            @click="goToEdit(slotProps.data.id)"
                                            severity="secondary"
                                            tooltip="Редактировать"
                                            variant="text"
                                        />
                                        <Button
                                            icon="pi pi-trash"
                                            rounded
                                            @click="openDeleteModal(slotProps.data)"
                                            severity="danger"
                                            variant="text"
                                        />
                                    </template>
                                </SpeedDial>
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

        <!-- Модальное окно подтверждения (PrimeVue Dialog) -->
        <Dialog v-model:visible="deleteConfirmationVisible" modal :style="{ width: '25rem' }" :breakpoints="{ '768px': '50vw', '425px': '90vw' }">
            <template #header>
                <span class="dark:text-surface-400 m-0 text-[17px] font-semibold"> Are you absolutely sure? </span>
            </template>
            <div class="text-surface-500 dark:text-surface-400 mb-1 block font-semibold">
                {{ deleteEntity?.id }} - <span v-if="deleteEntity?.name">{{ deleteEntity?.name }} - will be deleted forever.</span>
            </div>
            <span class="text-red-500">
                <b>
                    <strong>All users will lose this role.</strong>
                </b>
            </span>
            <div class="mt-2 flex justify-end gap-2">
                <Button type="button" size="small" label="Cancel" severity="secondary" raised @click="closeDeleteModal" />
                <Button type="button" size="small" label="Confirm" severity="danger" raised @click="handleDeleteConfirmed" />
            </div>
        </Dialog>
    </Layout>
</template>
