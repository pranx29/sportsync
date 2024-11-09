<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Human Resources',
            'Engineering',
            'Marketing',
            'Sales',
            'Finance',
            'Customer Support'
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}
