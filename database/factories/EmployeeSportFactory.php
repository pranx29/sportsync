<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EmployeeSportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\User::factory(),
            'sport_id' => \App\Models\Sport::factory(),
            'interest_level' => $this->faker->randomElement(['low', 'medium', 'high']),
            'skill_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
        ];
    }
}
