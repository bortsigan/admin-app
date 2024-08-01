<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'contact_no' => fake()->phoneNumber(),
            'birthday' => fake()->date(),
            'role_id' => Role::ROLE_CLIENT,
            'user_id' => rand(1,2) # assuming that there's user id 1 and 2 already that are admins
        ];
    }

    /**
     * Indicate that the user should have a specific role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withRole($roleId): Factory
    {
        return $this->state([
            'role_id' => $roleId,
        ]);
    }

    public function admin()
    {
        return $this->state([
            'role_id' => Role::ROLE_ADMIN,
        ]);
    }
}
