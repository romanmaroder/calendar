import { Ref } from 'vue';
import { type User } from '@/types';

/*export function useSingleElement(items?: object, id?: number): object | undefined {
    const item = ref<Arrayable<any>>(items);

    if (!item.value) return {};
    if (!id) return {};

    item.value = item.value.filter((val: any) => val.id !== id);
}*/

/*Совместно с Алиса ИИ*/
export function useRows<T extends User>(rows?: Ref<T[]>, selectedRows?: Ref<T[]>): void {
    // Проверяем, что оба аргумента переданы и не null
    if (!rows || !selectedRows) {
        return;
    }
    // Фильтруем rows: оставляем только те, которых нет в selectedRows
    rows.value = rows.value.filter((row: T) => {
        return !selectedRows.value.some((selectedRow: T) => {
            // Сравниваем по id (надежнее, чем по объекту)
            return selectedRow.id === row.id;
        });
    });
    selectedRows.value = [];
}

export function workingWithTableItems() {
    return { useRows };
}
