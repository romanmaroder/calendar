export function getYyMmDd(date?: Date): string {
    if (!date) return '';

    return  new Date(date.getTime() - date.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
}

export function useDate() {
    return { getYyMmDd };
}
