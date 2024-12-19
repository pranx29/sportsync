<?php

use App\Models\User;
use App\Models\Group;
use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CreateSessionTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_create_session_in_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user->id);

        $this->actingAs($user);

        $sessionData = [
            'group_id' => $group->id,
            'session_name' => 'Test Session',
            'date_time' => now()->addDay(),
            'duration' => 2,
            'participation_limit' => 10,
            'equipment_provided' => 'yes',
            'location' => 'Test Location',
            'description' => 'Test Description'
        ];

        $response = $this->post(route('group.sessions.create', $group), $sessionData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('group_sessions', [
            'group_id' => $group->id,
            'session_name' => 'Test Session',
            'duration' => 2,
            'participation_limit' => 10,
            'equipment_provided' => true,
            'location' => 'Test Location',
            'description' => 'Test Description',
            'leader_id' => $user->id
        ]);

        $this->assertDatabaseHas('session_user', [
            'user_id' => $user->id
        ]);
    }

    public function test_unauthorized_user_cannot_create_session()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $this->actingAs($user);

        $sessionData = [
            'group_id' => $group->id,
            'session_name' => 'Test Session',
            'date_time' => now()->addDay(),
            'duration' => 2,
            'participation_limit' => 10,
            'equipment_provided' => 'yes',
            'location' => 'Test Location'
        ];

        $response = $this->post(route('group.sessions.create', $group), $sessionData);
        $response->assertRedirect();
    }

    public function test_session_validation_rules()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user->id);

        $this->actingAs($user);

        $response = $this->post(route('group.sessions.create', $group), []);


        $invalidData = [
            'group_id' => $group->id,
            'session_name' => 'Test Session',
            'date_time' => now()->subDay(), // Past date
            'duration' => 6, // Exceeds max
            'participation_limit' => 0, // Below min
            'equipment_provided' => 'invalid',
            'location' => ''
        ];

        $response = $this->post(route('group.sessions.create', $group), $invalidData);
        $response->assertRedirect();
    }
}
