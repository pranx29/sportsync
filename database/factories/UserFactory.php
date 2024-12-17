<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Sport;
use App\Models\Profile;
use Illuminate\Support\Str;
use App\Models\Availability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'role' => 'employee',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            Profile::factory()->create([
                'user_id' => $user->id,
            ]);
            $user->update(['is_profile_created' => true]);

            $sports = Sport::factory(3)->create(); // Create 3 sports
            foreach ($sports as $sport) {
                DB::table('employee_sport')->insert([
                    'user_id' => $user->id,
                    'sport_id' => $sport->id,
                    'interest_level' => $this->faker->randomElement(['low', 'medium', 'high']),
                    'skill_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Availability::factory(3)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
