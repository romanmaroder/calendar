import { computed } from 'vue';

export const useStatus = (status: boolean | undefined) => {
    const info = computed(() => {
        if (status === undefined) return {};

        return status
            ? { label: 'active', severity: 'success' }
            : { label: 'disabled', severity: 'warn' };
    });

    return {
        label: info.value.label,
        severity: info.value.severity,
        // Можно добавить и другие поля
    };
};
