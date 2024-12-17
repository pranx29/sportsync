<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'department_id' => \App\Models\Department::factory(),
            'role_id' => \App\Models\Role::factory(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'profile_image' => $this->faker->imageUrl(),
            'date_of_birth' => $this->faker->date(),
        ];
    }
}

