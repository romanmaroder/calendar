// src/composables/utils/phone/useBranchCountryPhone.ts
import { Ref, watch } from 'vue';
import type { Branch, Country } from '@/types';

export const useBranchCountryPhone = (
    form: any, // можно типизировать как Inertia Form, если есть общий тип
    branches: Ref<Branch[]> | undefined, // Ref<Branch[]> | undefined
    countries: Ref<Country[]>, // Ref<Country[]>
) => {
    watch(
        () => form.branch_id,
        (newId,oldId) => {
            if (!newId) {
                form.country_id = null;
                countries.value = [];
                if (form.phone) {
                    form.reset('phone');
                }
                return;
            }

            const selectedBranch = branches?.value?.find(
                (b: Branch) => b.id === Number(newId),
            );

            if (selectedBranch?.country) {
                form.country_id = selectedBranch.country.id;
                countries.value = [selectedBranch.country];
                form.reset('phone');
                if (oldId) {
                    form.phone = '';
                }
            } else {
                form.country_id = null;
                countries.value = [];
            }
        },
        { immediate: true },
    );
};
