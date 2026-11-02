<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'nik' => fake()->unique()->numerify('########'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'department' => 'Instrument & Control',
            'position' => fake()->jobTitle(),
            'role' => 'teknisi',
            'status' => 'active',
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
