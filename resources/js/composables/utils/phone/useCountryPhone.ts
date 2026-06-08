// @/composables/useCountryPhone.ts
import { useValidatePhone } from '@/composables/utils/phone/useValidatePhone';
import { Country } from '@/types';
import { computed, Ref } from 'vue';

interface UseCountryPhoneOptions {
    countries: Ref<Country[]> | undefined;
    form: {
        resolved_country_id: number | null;
        phone: string;
    };
}

export const useCountryPhone = ({ countries, form }: UseCountryPhoneOptions) => {
    const { generateFormattedPhoneExamples } = useValidatePhone();

    // Проверка наличия функции форматирования
    if (!generateFormattedPhoneExamples) {
        console.error('Ошибка: generateFormattedPhoneExamples не доступна в useCountryPhone');
        return {
            country: computed(() => null),
            phoneMask: computed(() => null),
            phoneRegex: computed(() => null),
            mask: computed(() => undefined),
        };
    }

    const country = computed(() => {
        if (countries?.value === undefined) {
            console.error('Ошибка: countries.value не определён в useCountryPhone');
            return null;
        }
        if (form.resolved_country_id !== null && form.resolved_country_id !== undefined) {
            const foundCountry = countries.value.find((country: Country) => country.id === Number(form.resolved_country_id));
            if (!foundCountry) {
                console.warn(`Страна с ID ${form.resolved_country_id} не найдена в списке countries`);
            }
            return foundCountry;
        }
        return null;
    });

    const phoneMask = computed(() => {
        if (country.value && country.value.phone_mask) {
            return country.value.phone_mask;
        }
        return null;
    });

    const phoneRegex = computed(() => {
        if (country.value && country.value.phone_regex) {
            return country.value.phone_regex;
        }
        return null;
    });

    const mask = computed(() => {
        if (phoneMask.value && phoneRegex.value) {
            try {
                return generateFormattedPhoneExamples(phoneRegex.value, 1, phoneMask.value).join();
            } catch (error) {
                console.error('Ошибка при генерации маски телефона:', error);
                return undefined;
            }
        }
        return undefined;
    });

    return {
        country,
        mask,
    };
};
