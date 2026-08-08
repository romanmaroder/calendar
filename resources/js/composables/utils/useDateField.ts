import { Ref, ref, watch } from 'vue';

export type DateFormat = 'iso' | 'local';

export interface UseDateFieldOptions {
    initialValue?: string | null;
    format?: DateFormat;
}

export function useDateField(options: UseDateFieldOptions = {}) {
    const { initialValue = null, format = 'local' } = options;

    // Важно: именно Ref<Date | null>
    const internalDate = ref<Date | null>(
        initialValue ? new Date(initialValue) : null
    );

    const formValue = ref<string>(initialValue ?? '');

    const updateFormValue = () => {
        if (!internalDate.value) {
            formValue.value = '';
            return;
        }

        if (format === 'iso') {
            formValue.value = internalDate.value.toISOString().slice(0, 10);
        } else {
            formValue.value = internalDate.value.toLocaleDateString('en-CA');
        }
    };

    watch(internalDate, updateFormValue, { immediate: true });

    const setFromString = (dateString: string | null) => {
        if (!dateString) {
            internalDate.value = null;
            formValue.value = '';
            return;
        }
        internalDate.value = new Date(dateString);
    };

    const getPayloadValue = (): string => formValue.value;
    const reset = () => {
        internalDate.value = null;
        formValue.value = '';
    };

    return {
        internalDate: internalDate as Ref<Date | null>,
        formValue,
        setFromString,
        getPayloadValue,
        reset,
    };
}
