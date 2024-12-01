<?php

namespace Database\Seeders;

use App\Models\Message;
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
            GroupSeeder::class
        ]);

    }
}
