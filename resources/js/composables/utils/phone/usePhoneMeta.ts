import { ref } from 'vue';
import axios from 'axios';

export const usePhoneMeta = (endpoint: string) => {
    const meta = ref<{
        phone_mask?: string;
        phone_regex?: string;
        country_code?: string;
    } | null>(null);

    const loading = ref(false);
    const error = ref<string | null>(null);

    const load = async (params?: Record<string, any>) => {
        loading.value = true;
        error.value = null;

        try {
            const res = await axios.get<{
                success: boolean;
                data?: any;
                message?: string;
            }>(endpoint, { params });

            if (res.data.success && res.data.data) {
                meta.value = res.data.data;
            } else {
                error.value = res.data.message || 'Не удалось получить настройки телефона';
            }
        } catch (e:unknown) {
            console.error(e);
            error.value = 'Ошибка соединения';
        } finally {
            loading.value = false;
        }
    };

    // При монтировании можно загрузить дефолт (если нужен)
    //onMounted(() => load());

    return { meta, loading, error, load };
};
