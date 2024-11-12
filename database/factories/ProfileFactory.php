<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
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
            'user_id' => User::factory(), 
            'department_id' => Department::inRandomOrder()->first()?->id, 
            'role_id' => Role::inRandomOrder()->first()?->id,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'profile_image' => $this->faker->imageUrl(100, 100, 'people', true), 
            'date_of_birth' => $this->faker->date('Y-m-d', '2006-01-01'), 
        ];
    }
}
