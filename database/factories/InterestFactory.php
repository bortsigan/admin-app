<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class InterestFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $interests = [
            'Swimming - '. rand(1,10), 
            'Basketball - '. rand(1,10), 
            'Badminton - '. rand(1,10), 
            'Football - '. rand(1,10), 
            'Tennis - '. rand(1,10), 
            'Running - '. rand(1,10), 
            'Cycling - '. rand(1,10), 
            'Reading - '. rand(14,110), 
            'Cooking - '. rand(11,101), 
            'Traveling - ' . rand(100, 200)];

        return [
            'name' => $this->faker->randomElement($interests)
        ];
    }
}
