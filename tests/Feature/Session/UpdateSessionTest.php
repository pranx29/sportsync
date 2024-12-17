<?php
use App\Models\Session;
use App\Models\User;
use App\Notifications\SessionUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;



class UpdateSessionTest extends TestCase
{
    use RefreshDatabase;
    public function test_session_leader_can_update_session()
    {

        $leader = User::factory()->create();
        $session = Session::factory()->create(['leader_id' => $leader->id]);

        $this->actingAs($leader);

        $response = $this->put(route('group.sessions.update', $session->id), [
            'session_name' => 'Updated Session',
            'date_time' => now()->addDays(1),
            'duration' => 2,
            'participation_limit' => 10,
            'equipment_provided' => 'yes',
            'location' => 'Updated Location',
            'description' => 'Updated description'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('group_sessions', [
            'id' => $session->id,
            'session_name' => 'Updated Session',
            'participation_limit' => 10,
            'location' => 'Updated Location'
        ]);

    }

    public function test_non_leader_cannot_update_session()
    {
        $leader = User::factory()->create();
        $nonLeader = User::factory()->create();
        $session = Session::factory()->create(['leader_id' => $leader->id]);

        $this->actingAs($nonLeader);

        $response = $this->put(route('group.sessions.update', $session->id), [
            'session_name' => 'Updated Session',
            'date_time' => now()->addDays(1),
            'duration' => 2,
            'participation_limit' => 10,
            'equipment_provided' => 'yes',
            'location' => 'Updated Location',
            'description' => 'Updated description'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_validation_fails_for_past_date()
    {
        $leader = User::factory()->create();
        $session = Session::factory()->create(['leader_id' => $leader->id]);

        $this->actingAs($leader);

        $response = $this->put(route('group.sessions.update', $session->id), [
            'session_name' => 'Updated Session',
            'date_time' => now()->subDay(),
            'duration' => 2,
            'participation_limit' => 10,
            'equipment_provided' => 'yes',
            'location' => 'Updated Location',
            'description' => 'Updated description'
        ]);

        $response->assertSessionHasErrors('date_time');
    }

    public function test_validation_fails_for_invalid_duration()
    {
        $leader = User::factory()->create();
        $session = Session::factory()->create(['leader_id' => $leader->id]);

        $this->actingAs($leader);

        $response = $this->put(route('group.sessions.update', $session->id), [
            'session_name' => 'Updated Session',
            'date_time' => now()->addDays(1),
            'duration' => 6, // max is 5
            'participation_limit' => 10,
            'equipment_provided' => 'yes',
            'location' => 'Updated Location',
            'description' => 'Updated description'
        ]);

        $response->assertSessionHasErrors('duration');
    }

    public function test_validation_fails_for_invalid_equipment_provided()
    {
        $leader = User::factory()->create();
        $session = Session::factory()->create(['leader_id' => $leader->id]);

        $this->actingAs($leader);

        $response = $this->put(route('group.sessions.update', $session->id), [
            'session_name' => 'Updated Session',
            'date_time' => now()->addDays(1),
            'duration' => 2,
            'participation_limit' => 10,
            'equipment_provided' => 'invalid',
            'location' => 'Updated Location',
            'description' => 'Updated description'
        ]);

        $response->assertSessionHasErrors('equipment_provided');
    }
}
