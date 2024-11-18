<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'employee')->first();

        DB::table('groups')->insert([
            [
                'name' => 'Futsal Enthusiasts',
                'description' => 'A group for futsal fans and players who engage in regular playing sessions, balancing their passion for futsal with their busy work schedules.',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/2018-10-18_Futsal_%28Boys_Bronze_medal_match%29_at_2018_Summer_Youth_Olympics_by_Sandro_Halank%E2%80%930035.jpg/640px-2018-10-18_Futsal_%28Boys_Bronze_medal_match%29_at_2018_Summer_Youth_Olympics_by_Sandro_Halank%E2%80%930035.jpg',
                'is_active' => true,
                'user_id' => $user->id,
                'sport_id' => Sport::where('name', 'Futsal')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basketball Lovers',
                'description' => 'A group for employees who are passionate about basketball, organizing regular playing sessions to stay active and build camaraderie within the organization.',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyHoYmYC0ACqldImwjvuHdCtQSEyS4KpFSNw&s',
                'is_active' => true,
                'user_id' => $user->id,
                'sport_id' => Sport::where('name', 'Basketball')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tennis Players',
                'description' => 'A group for employees who love tennis, organizing regular playing sessions to stay active and foster teamwork within the organization.',
                'image' => 'https://media.newyorker.com/photos/66d20d1dc014ba23ca404aa5/16:9/w_1280,c_limit/Thomas-Qinwen-Zheng-GettyImages-2168268902.jpg',
                'is_active' => true,
                'user_id' => $user->id,
                'sport_id' => Sport::where('name', 'Tennis')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        // Assign group members
        $groups = Group::all();
        $users = User::where('role', 'employee')->get();

        foreach ($groups as $group) {
            $group->users()->attach($users->random(rand(1, 5))->pluck('id')->mapWithKeys(function ($id) {
                return [$id => ['created_at' => now(), 'updated_at' => now()]];
            })->toArray());
        }
    }
}
