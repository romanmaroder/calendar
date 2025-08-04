// eslint-disable-next-line @typescript-eslint/no-unused-vars
export function getFullname(name?: string, middlename?: string, surname?: string): string {
    let fullname = '';

    for (let i = 0; i < arguments.length; i++) {
        // eslint-disable-next-line prefer-rest-params
        fullname += `${arguments[i]} `;
    }

    return fullname;
}

export function useFullname() {
    return { getFullname };
}
