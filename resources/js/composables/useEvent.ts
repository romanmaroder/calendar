import { onMounted, onUnmounted } from 'vue'

/*Добавления и удаления слушателя событий DOM*/
export function useEventListener(target: EventTarget, event: string, callback: EventListener) {
    onMounted(() => target.addEventListener(event, callback))
    onUnmounted(() => target.removeEventListener(event, callback))
}