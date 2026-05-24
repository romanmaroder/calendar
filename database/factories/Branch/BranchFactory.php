<?php

namespace Database\Factories\Branch;

use App\Models\Branch\Branch;
use App\Models\Country\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
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
            'contact'=>$this->faker->address(),
            'avatar' => null,
            'status'=>$this->faker->boolean(100),
            'country_id' => $this->faker->numberBetween(1, 1),
        ];
    }
}
