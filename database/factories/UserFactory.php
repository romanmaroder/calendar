<?php

namespace Database\Factories;

use App\Models\Branch\Branch;
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
        $branch = Branch::with('country')->first();
        if ($this->isAdmin) {
            return [
                'name' => 'Admin',
                'surname' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => 'admin',
                'phone' => $branch->country->generatePhoneNumber(),
                'branch_id' => $branch->id,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
        }

        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName(),
            'middleName' => $this->faker->firstName('male'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'phone' => $branch->country->generatePhoneNumber(),
            'branch_id' => $branch->id,
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
