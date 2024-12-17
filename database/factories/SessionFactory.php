<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_id' => \App\Models\Group::factory(),
            'session_name' => fake()->words(3, true),
            'date_time' => fake()->dateTimeBetween('now', '+30 days'),
            'duration' => fake()->numberBetween(30, 180),
            'participation_limit' => fake()->optional()->numberBetween(5, 30),
            'equipment_provided' => fake()->boolean(),
            'location' => fake()->address(),
            'description' => fake()->optional()->paragraph(),
            'leader_id' => \App\Models\User::factory()
        ];
    }
}
