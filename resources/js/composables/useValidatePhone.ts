export function generatePhoneExamples(regex: string, count: number = 5): string[] {
    // Убираем ^ и $, если есть
    const cleanRegex = regex.replace(/^[\^]|\$$/g, '');

    // Парсим префикс (всё до первого \d)
    const prefixMatch = cleanRegex.match(/^([^\d]*)\d/);
    const prefix = prefixMatch ? prefixMatch[1] : '';

    // Считаем общее количество цифр
    const digitMatches = cleanRegex.match(/(\d\{?(\d+)\}?|\d)/g);
    let totalDigits = 0;

    digitMatches?.forEach(match => {
        if (match.includes('{')) {
            totalDigits += parseInt(match.match(/\{(\d+)\}/)![1], 10);
        } else {
            totalDigits++;
        }
    });

    // Генерируем примеры
    const examples: string[] = [];
    for (let i = 0; i < count; i++) {
        // Генерируем случайную последовательность цифр
        const digits = Array.from({ length: totalDigits }, () =>
            Math.floor(Math.random() * 10)
        ).join('');

        // Форматируем с префиксом
        const example = prefix + digits;
        examples.push(example);
    }
    return examples;
}
export function generateFormattedPhoneExamples(
    regex: string,
    count: number = 5,
    formatTemplate?: string
): string[] {
    const basicExamples = generatePhoneExamples(regex, count);

    if (!formatTemplate) {
        return basicExamples;
    }

    return basicExamples.map(example => {
        // Извлекаем цифры из базового примера
        const digits = example.replace(/\D/g, '');
        let result = formatTemplate;
        let digitIndex = 0;

        result = result.replace(/#/g, () => {
            if (digitIndex < digits.length) {
                return digits[digitIndex++];
            }
            return '_';
        });
        return result;
    });
}




export function useValidatePhone(){
    return { generatePhoneExamples ,generateFormattedPhoneExamples};
}
