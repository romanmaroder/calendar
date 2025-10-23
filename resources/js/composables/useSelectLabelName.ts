export function selectLabelName(data?: Array<{id: number, name: string}>, id?: number|string): string |undefined {
    if (!data) return undefined;

    return  data?.find(item => item.id === id)?.name;
}


