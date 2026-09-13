<script setup lang="ts">
import { useToast } from 'primevue/usetoast';
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import InputError from '@/components/InputError.vue';
import { inject, Ref } from 'vue';
import { RolesTranslations } from '@/types/translations';

const props = defineProps<{
    role?: { id: number; name: string };
    assignedPermissions?: number[];
    permissions: Array<{ id: number; name: string }>;
}>();

const translations = inject<Ref<RolesTranslations>>('formTranslations');

const emit = defineEmits<{
    (e: 'submit-success'): void;
}>();

const isEdit = !!props.role;
const toast = useToast();

const form = useForm({
    name: props.role?.name ?? '',
    permission_ids: props.assignedPermissions ?? [],
});

const submit = () => {
    const url = isEdit ? route('admin.roles.update', { role: props.role!.id }) : route('admin.roles.store');

    const method = isEdit ? 'put' : 'post';

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Успех',
                detail: isEdit ? 'Роль обновлена' : 'Роль создана',
                life: 3000,
            });
            emit('submit-success');
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

const cancel = () => {
    router.visit(route('admin.roles.index'));
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="mb-4">
            <FloatLabel variant="on">
                <InputText v-model="form.name" class="w-full" :placeholder="translations?.placeholder?.name" />
                <label>{{ translations?.placeholder?.name }}</label>
            </FloatLabel>
            <InputError :message="form.errors.name" />
        </div>

        <div class="mb-4">
            <label class="mb-2 block font-medium">{{ translations?.placeholder?.permissions }}</label>
            <MultiSelect
                v-model="form.permission_ids"
                :options="permissions"
                option-value="id"
                option-label="name"
                :placeholder="translations?.placeholder?.permissions"
                class="w-full"
            />
            <InputError :message="form.errors.permission_ids" />
        </div>

        <div class="flex justify-end gap-2">
            <Button size="small" :label="translations?.button?.save" type="submit" :loading="form.processing" raised />
            <Button size="small" severity="secondary" @click="cancel" :label="translations?.button?.cancel" raised />
        </div>
    </form>
</template>
