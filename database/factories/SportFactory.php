<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sport>
 */
class SportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,  // Ensure the name is unique
            'category' => $this->faker->randomElement(['team', 'individual']),
            'focus' => $this->faker->randomElement(['physical', 'intellectual']),
        ];

    }
}
