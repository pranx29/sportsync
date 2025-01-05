<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\GroupSeeder;
use Database\Seeders\SportSeeder;
use Database\Seeders\VenueSeeder;
use Database\Seeders\DepartmentSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@ss.com',
            'password' => bcrypt('password'),
        ]);

        // Admin
        User::factory()->create([
            'first_name' => 'Franklin',
            'last_name' => 'Clin',
            'email' => 'fc@admin.com',
            'role' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        User::factory(5)->create();

        $this->call([
            DepartmentSeeder::class,
            RoleSeeder::class,
            SportSeeder::class,
            GroupSeeder::class,
            VenueSeeder::class,
        ]);

    }
}
