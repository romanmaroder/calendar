<?php

namespace Database\Factories\Company;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->realText(10),
            'contact' => $this->faker->address(),
            'info' => $this->faker->realText(15),
            'logo' => $this->faker->imageUrl(),
            'country_code' => $this->faker->countryCode(),
            'currency_code' => $this->faker->currencyCode(),
        ];
    }
}
