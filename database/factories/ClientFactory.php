<?php

namespace Database\Factories;

use App\Models\Branch\Branch;
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
        $branch = Branch::inRandomOrder()->with('country')->first();
        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'middleName' => fake()->firstName('male'),
            'phone' => $branch->country->generatePhoneNumber(),
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
