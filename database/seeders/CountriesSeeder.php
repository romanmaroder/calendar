<?php

namespace Database\Seeders;

use App\Models\Country\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Пример данных (можно взять из ISO 3166-1)
        $countries = [
            [
                'code' => 'RU',
                'name' => 'Россия',
                'iso_code' => 'RUS',
                'phone_code' => '+7',
                'phone_regex' => '/^\+7\(\d{3}\)\d{3} \d{2} \d{2}$/',
                'phone_mask'=>'+7(999)999-99-99',
                'currency' => 'RUB',
                'active' => true,
            ],
//            [
//                'code' => 'US',
//                'name' => 'США',
//                'iso_code' => 'USA',
//                'phone_code' => '+1',
//                'phone_regex' => '/^\+1\d{10}$/',
//                'currency' => 'USD',
//                'active' => true,
//            ],
//            [
//                'code' => 'DE',
//                'name' => 'Германия',
//                'iso_code' => 'DEU',
//                'phone_code' => '+49',
//                'phone_regex' => '/^\+49\d{10,12}$/',
//                'currency' => 'EUR',
//                'active' => true,
//            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']], // уникальный ключ для поиска
                $country
            );
        }

        //Country::factory(5)->create();
    }
}
