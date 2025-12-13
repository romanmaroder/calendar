import { Ref } from 'vue';
import { Branch, Client, type User } from '@/types';

/*Совместно с Алиса ИИ*/
export function useRows<T extends User | Client |Branch>(rows?: Ref<T[]>, selectedRows?: Ref<T[]>): void {
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
