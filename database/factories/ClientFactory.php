<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'middleName' => fake()->word(1),
            'phone' => fake()->numerify('#############'),
            'comment' => fake()->text(20),
            'blacklist' => fake()->boolean(),
            'prepayment' => fake()->boolean(),
            'discount' => fake()->numberBetween(1,10),
            'records' => fake()->numberBetween(1,100),
            'total' => fake()->numberBetween(1,10000),
            'source'=>fake()->word(),
            'avatar' => null,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
