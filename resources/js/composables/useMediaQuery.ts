import { onBeforeUnmount, onMounted, ref, Ref } from 'vue';

/**
 * Reactively tracks whether the window matches a media query.
 * @param query - Media query string (e.g., '(min-width: 640px)') or a number (treated as min-width in px)
 * @returns Ref<boolean> - true if query matches, false otherwise
 */
export function useMediaQuery(query: string | number): Ref<boolean> {
    const matches = ref(false);

    // Преобразуем число в медиа‑запрос
    const mediaQuery = typeof query === 'number' ? `(min-width: ${query}px)` : query;

    let mediaQueryList: MediaQueryList | undefined;

    const update = () => {
        if (mediaQueryList) {
            matches.value = mediaQueryList.matches;
        }
    };

    onMounted(() => {
        try {
            mediaQueryList = window.matchMedia(mediaQuery);
            update();

            // Подписываемся на изменения
            mediaQueryList.addEventListener('change', update);
        } catch (err) {
            console.warn('Failed to set up media query:', err);
            // В случае ошибки оставляем matches.value = false
        }
    });

    onBeforeUnmount(() => {
        // Проверяем, что mediaQueryList существует и имеет метод removeEventListener
        if (mediaQueryList && mediaQueryList.removeEventListener) {
            mediaQueryList.removeEventListener('change', update);
        }
        // Явно обнуляем, чтобы избежать утечек
        mediaQueryList = undefined;
    });

    return matches;
}
