<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class GroupSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'session_name' => $this->faker->sentence,
            'date_time' => $this->faker->dateTimeBetween('now', '+2 months'),
            'duration' => $this->faker->numberBetween(1, 5),
            'participation_limit' => $this->faker->optional()->numberBetween(1, 100),
            'equipment_provided' => $this->faker->boolean(50),
            'location' => $this->faker->address,
            'description' => $this->faker->optional()->paragraph,
            'leader_id' => User::factory(),
        ];
    }
}
