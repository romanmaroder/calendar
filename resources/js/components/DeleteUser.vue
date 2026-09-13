<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { inject, Ref, ref } from 'vue';

// Components
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { DialogTranslations } from '@/types/translations';

const translations = inject<Ref<DialogTranslations>>('dialog');

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const deleteUser = (e: Event) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-6">
        <HeadingSmall :title="translations?.profile_delete_account_title" :description="translations?.profile_delete_account_description" />
        <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">{{ translations?.profile_warning }}</p>
                <p class="text-sm">{{ translations?.profile_warning_text }}</p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive">{{ translations?.delete_account }}</Button>
                </DialogTrigger>
                <DialogContent class="flex flex-col">
                    <form class="space-y-6" @submit="deleteUser">
                        <DialogTitle>{{ translations?.dialog_title }}</DialogTitle>
                        <DialogDescription class="mb-3 block">
                            {{ translations?.dialog_description }}
                        </DialogDescription>
                        <div class="grid gap-2">
                            <Label for="password" class="sr-only">{{ translations?.password }}</Label>
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                ref="passwordInput"
                                v-model="form.password"
                                :placeholder="translations?.password"
                            />
                            <InputError :message="form.errors.password" />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" @click="closeModal"> {{ translations?.cancel }} </Button>
                            </DialogClose>

                            <Button variant="destructive" :disabled="form.processing">
                                <button type="submit">{{ translations?.delete_account }}</button>
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
