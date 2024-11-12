<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

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

        $this->call([
            DepartmentSeeder::class,
            RoleSeeder::class,
            SportSeeder::class,
        ]);

        User::factory(10)->withProfile()->create();
        User::factory(5)->create(); 

    }
}
