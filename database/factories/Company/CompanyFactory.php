<?php

namespace Database\Factories\Company;

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
            'name' => 'New Company',
            'phone' => $country->generatePhoneNumber(),
            'is_primary'=>$this->faker->boolean(100),
            'description' => 'Company Description',
            'contact' => $this->faker->address(),
            'info' => 'What does the company do?',
            'avatar' => null,
            'country_id' => $this->faker->numberBetween(1, 1),
        ];
    }
}
