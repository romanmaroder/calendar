<?php

namespace Database\Factories\Company;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Country\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
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
        $country = Country::first();
        return [
            'name' => $this->faker->company(),
            'phone' => $country->generatePhoneNumber(),
            'description' => $this->faker->realText(10),
            'contact' => $this->faker->address(),
            'info' => $this->faker->realText(15),
            'avatar' => $this->faker->imageUrl(),
            'country_id' => $this->faker->numberBetween(1, 1),
        ];
    }
}
