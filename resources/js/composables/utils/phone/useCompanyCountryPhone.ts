// src/composables/utils/phone/useCompanyCountryPhone.ts
import { Ref, watch } from 'vue';
import type { Company, Country } from '@/types';

export const useCompanyCountryPhone = (
    form: any,
    companies: Ref<Company[]> | undefined, // Ref<Company[]> | undefined
    countries: Ref<Country[]> , // Ref<Country[]>
) => {
    watch(
        () => form.company_id,
        (newId,oldId) => {
            if (!newId) {
                form.resolved_country_id = null;
                countries.value = [];
                if (form.phone) {
                    form.reset('phone');
                }
                return;
            }

            const selectedCompany = companies?.value?.find(
                (c: Company) => c.id === Number(newId),
            );

            if (selectedCompany?.country) {
                form.resolved_country_id = selectedCompany.country.id;
                countries.value = [selectedCompany.country];
                form.reset('phone');
                if (oldId) {
                    form.phone = '';
                }
            } else {
                form.resolved_country_id = null;
                countries.value = [];
            }
        },
        { immediate: true },
    );
};
