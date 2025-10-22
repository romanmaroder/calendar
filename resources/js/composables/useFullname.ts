// eslint-disable-next-line @typescript-eslint/no-unused-vars
export function getFullname(name?: string, middlename?: string, surname?: string): string {
    let fullname = '';

    for (let i = 0; i < arguments.length; i++) {
        // eslint-disable-next-line prefer-rest-params
        if (arguments[i] === undefined || arguments[i] === null) {break;}
        // eslint-disable-next-line prefer-rest-params
        fullname += `${arguments[i][0].toUpperCase() +  arguments[i].slice(1)} `;
    }

    return fullname;
}

export function useFullname() {
    return { getFullname };
}
