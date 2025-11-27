<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{

    protected bool $isAdmin = false;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        if ($this->isAdmin) {
            return [
                'name' => 'Admin',
                'surname' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => 'admin',
                'phone' => '+7 (949) 123-45-67',
                'branch_id' => $this->faker->numberBetween(1, 3),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
        }

        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'phone' => $this->faker->phoneNumber(),
            'branch_id' => $this->faker->numberBetween(1, 3),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        $this->isAdmin = true;
        return $this;
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
