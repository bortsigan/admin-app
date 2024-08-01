<?php

namespace Database\Factories;

use App\Models\ClientInterest;
use App\Models\User;
use App\Models\Interest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientInterest>
 */
class ClientInterestFactory extends Factory
{
    protected $model = ClientInterest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'interest_id' => Interest::factory(),
        ];
    }
}
