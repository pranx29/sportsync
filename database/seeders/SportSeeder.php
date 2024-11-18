<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            ['name' => 'Futsal', 'category' => 'team', 'focus' => 'physical'],
            ['name' => 'Basketball', 'category' => 'team', 'focus' => 'physical'],
            ['name' => 'Tennis', 'category' => 'individual', 'focus' => 'physical'],
            ['name' => 'Cricket', 'category' => 'team', 'focus' => 'intellectual'],
            ['name' => 'Swimming', 'category' => 'individual', 'focus' => 'physical']
        ];
        
        foreach ($sports as $sport) {
            Sport::create($sport);
        }
    }
}
