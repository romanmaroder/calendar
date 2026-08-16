<?php

namespace Database\Factories;

use App\Models\Branch\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Random\RandomException;

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
        $branch = Branch::with('company.country')->first();
        if ($this->isAdmin) {
            return [
                'name' => 'Admin',
                'surname' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => \Hash::make('admin'),
                //'phone' => $branch->company->country->generatePhoneNumber(),
                'phone' => '+7(949)111 11 11',
                'branch_id' => $branch->id,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
        }



        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName(),
            'middleName' => $this->faker->firstName('male'),
            'email' => $this->faker->unique()->email(),
            'password' => \Hash::make('password'),
            'phone' => $branch->company->country->generatePhoneNumber(),
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

    public function temporaryEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'user' .random_int(1, 5000) . '@admincreate.com',
        ]);
    }

    public function requiresPasswordChange(): static
    {
        return $this->state(fn (array $attributes) => [
            'requires_password_change' => true,
        ]);
    }
}
