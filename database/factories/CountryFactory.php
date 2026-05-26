<?php

namespace Database\Factories;

use App\Models\Country\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{

    /* public function definition(): array
     {
         return [
             'code' => $this->faker->unique()->countryCode(),
             'name' => $this->faker->unique()->name(),
             'iso_code' => $this->faker->countryISOAlpha3(),
             'phone_code' => $this->faker->randomNumber(1,3),
             'phone_regex' => [
                     'RU' => '/^\+7\d{10}$/',
                     'US' => '/^\+1\d{10}$/',
                     'DE' => '/^\+49\d{10,12}$/',
                 ][$this->model->code] ?? null,
             'currency' => $this->faker->currencyCode(),
             'active' => $this->faker->boolean(),
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
         ];
     }*/
    public function definition(): array
    {
        // Базовые данные
        $code = $this->faker->unique()->countryCode();
        $name = $this->faker->country();

        // Телефонные коды и регэксы для известных стран
        //
        $phoneCodeMap = [
            'RU' => '+7',
            //'US' => '+1',
            //'DE' => '+49',
            // Можно расширить список
        ];

        /* Форматы телефонов под регулярное выражение
            +79991234567
        */
        $phoneRegexMap = [
            'RU' => '/^\+7\(\d{3}\)\d{3} \d{2} \d{2}$/',
            //'US' => '/^\+1\d{10}$/',
            //'DE' => '/^\+49\d{10,12}$/',
        ];

        // Валюты по странам
        $currencyMap = [
            'RU' => 'RUB',
            //'US' => 'USD',
            //'DE' => 'EUR',
        ];

        return [
            'code' => $code,
            'name' => $name,
            'iso_code' => $this->faker->countryISOAlpha3(),
            'phone_code' => $phoneCodeMap[$code] ?? $this->faker->optional()->numberBetween(1, 999),
            'phone_regex' => $phoneRegexMap[$code] ?? null,
            'currency' => $currencyMap[$code] ?? $this->faker->optional()->currencyCode(),
            'active' => $this->faker->boolean(80), // 80% вероятность active=true
        ];
    }
}
