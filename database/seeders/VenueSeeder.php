<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $venues = [
            'Stadium',
            'Arena',
            'Gym',
            'Field',
            'Court',
            'Pool',
            'Track',
            'Rink',
        ];

        foreach ($venues as $name) {
            Venue::create(['name' => $name]);
        }
    }
}
