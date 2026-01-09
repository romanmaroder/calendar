<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
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
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->realText(10),
            'contact' => $this->faker->address(),
            'info' => $this->faker->realText(15),
            'avatar' => $this->faker->imageUrl(),
            'country_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}
