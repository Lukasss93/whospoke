<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(8, true),
            'telegram_id' => fake()->unique()->randomNumber(8),
            'slack_id' => fake()->unique()->randomNumber(8),
            'name' => fake()->name(),
            'email' => null,
            'email_verified_at' => null,
            'password' => null,
            'remember_token' => Str::random(10),
        ];
    }
}
