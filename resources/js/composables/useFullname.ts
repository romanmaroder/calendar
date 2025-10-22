interface Data {
    name?: string;
    middlename?: string;
    surname?: string;
}

export function getFullname(data: Data): string {
    const name = data.name ? data.name[0]?.toUpperCase() + data.name?.slice(1) : '';
    const middlename = data.middlename ? data.middlename[0]?.toUpperCase() + data.middlename?.slice(1) : '';
    const surname = data.surname ? data.surname[0]?.toUpperCase() + data.surname?.slice(1) : '';

    return `${name} ${middlename} ${surname}`;
}

export function useFullname() {
    return { getFullname };
}
