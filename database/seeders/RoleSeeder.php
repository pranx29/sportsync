<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Manager',
            'Team Lead',
            'Developer',
            'Analyst',
            'Designer',
            'Support Specialist'
        ];

        foreach ($roles as $title) {
            Role::create(['title' => $title]);
        }
    }
}
