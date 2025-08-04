export function getPhone(phoneNumber?: string): string {
    if (!phoneNumber) return '';

    const number = phoneNumber.replace(/[- )(]/g, '');

    return `${number}`;
}

export function usePhoneLink() {
    return { getPhone };
}
