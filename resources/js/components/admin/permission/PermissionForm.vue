<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { router, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { route } from 'ziggy-js';
import { inject, onMounted, Ref } from 'vue';
import { PermissionTranslations } from '@/types/translations';

const props = defineProps<{
    permission?: { id: number; name: string };
}>();


const translations = inject<Ref<PermissionTranslations>>('formTranslations');

const emit = defineEmits<{
    (e: 'submit-success'): void;
    (e: 'submit-error', errors?: any): void;
}>();

const isEdit = !!props.permission;
const toast = useToast();

const form = useForm<{ name: string }>({
    name: props.permission?.name ?? '',
});

const submit = () => {
    const url = isEdit ? route('admin.permissions.update', { permission: props.permission!.id }) : route('admin.permissions.store');
    const method = isEdit ? 'put' : 'post';
    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Успех',
                detail: isEdit ? translations?.toast?.update : translations?.toast?.create,
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
    router.visit(route('admin.permissions.index'));
};

onMounted(()=>{
    console.log(translations)
})
</script>

<template>
    <form @submit.prevent="submit">
        <div class="mb-4">
            <FloatLabel variant="on">
                <InputText id="name" v-model="form.name" class="w-full"
                           :placeholder="translations?.placeholder?.name" />
                <label>{{translations?.placeholder?.name}}</label>
            </FloatLabel>
            <InputError :message="form.errors.name" />
        </div>
        <div class="flex justify-end gap-2">
            <Button
                size="small"
                type="submit"
                :loading="form.processing"
                raised
                :label="isEdit === false ? translations?.button?.save : translations?.button?.update"
            />
            <Button size="small"
                severity="secondary" @click="cancel" :label="translations?.button?.cancel" raised />
        </div>
    </form>
</template>
