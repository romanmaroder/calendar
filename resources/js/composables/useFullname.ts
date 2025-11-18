interface FullnameData {
    name?: string;
    middlename?: string;
    surname?: string;
}

interface GetFullnameOptions {
    separator?: string;
    skipEmpty?: boolean;
}

function capitalize(str: string | undefined): string {
    return str?.length ? str[0].toUpperCase() + str.slice(1).toLowerCase() : '';
}

export function getFullname( data: FullnameData, options: GetFullnameOptions = {}): string {
    const { separator = ' ', skipEmpty = true } = options;

    const parts = [
        capitalize(data.name),
        capitalize(data.middlename),
        capitalize(data.surname)
    ];

    const filteredParts = skipEmpty ? parts.filter(part => part.length > 0): parts;

    return filteredParts.join(separator);
}

export function useFullname() {
    return { getFullname };
}