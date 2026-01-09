<?php

namespace Database\Factories\Branch;

use App\Models\Branch\Branch;
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
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->realText(10),
            'contact'=>$this->faker->address(),
            'avatar' => $this->faker->imageUrl(),
            'status'=>$this->faker->boolean(100),
            'country_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}
