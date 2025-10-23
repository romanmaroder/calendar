export function dateLabelName(date?: Date): string {
    if (!date) return '';

    return new Date(date.getTime() - date.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
}

export function selectLabelName(data?: Array<{ id: number; name: string }>, id?: number | string): string | undefined {
    if (!data) return undefined;

    return data?.find((item) => item.id === id)?.name;
}

export function useLabelName() {
    return {
        dateLabelName,
        selectLabelName,
    };
}
