interface Data {
    name?: string;
    middlename?: string;
    surname?: string;
}

export function getFullname(data: Data): string {
    const name = data.name ? data.name[0]?.toUpperCase() + data.name?.slice(1).toLowerCase() : '';
    const middlename = data.middlename ? data.middlename[0]?.toUpperCase() + data.middlename?.slice(1).toLowerCase() : '';
    const surname = data.surname ? data.surname[0]?.toUpperCase() + data.surname?.slice(1).toLowerCase() : '';

    if (name.length !== 0 || middlename.length !== 0 || middlename.length !== 0) {
        return `${name} ${middlename} ${surname}`;
    }
    return '';
}

export function useFullname() {
    return { getFullname };
}
