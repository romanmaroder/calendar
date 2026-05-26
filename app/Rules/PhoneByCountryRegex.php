<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class PhoneByCountryRegex implements ValidationRule
{
    protected string $countryId;

    public function __construct(string $countryId)
    {
        $this->countryId = $countryId;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $country = DB::table('countries')
            ->where('id', $this->countryId)
            ->first();

        if (!$country) {
            $fail('Для выбранной страны не найдена запись в базе данных.');
            return;
        }

        if (empty($country->phone_regex)) {
            $fail('Для страны ' . ($country->name ?? 'неизвестной') . ' не задан шаблон валидации телефона.');
            return;
        }

        // НЕ очищаем номер — передаём исходное значение
        $inputValue = (string) $value;

        $pattern = $country->phone_regex;
        if ($pattern[0] !== '/') {
            $pattern = '/' . $pattern . '/';
        }

        $matches = preg_match($pattern, $inputValue);

        if ($matches === false) {
            $fail('Ошибка в шаблоне валидации для страны ' . $country->name);
            return;
        }

        if ($matches !== 1) {
            $fail("Номер телефона не соответствует формату для страны {$country->name}.");
        }
    }

}
