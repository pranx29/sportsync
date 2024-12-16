<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->optional()->sentence,
            'image' => $this->faker->imageUrl(),
            'is_active' => $this->faker->boolean(90), 
            'user_id' => User::factory(),
            'sport_id' => Sport::factory(),  
        ];
    }
}
