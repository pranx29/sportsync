<?php
use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Session;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SessionUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CancelSessionTest extends TestCase
{
    use RefreshDatabase;
    public function test_session_leader_can_cancel_session()
    {
        $this->actingAs($user = User::factory()->create());
        $group = Group::factory()->create();
        $session = Session::factory()->create([
            'group_id' => $group->id,
            'leader_id' => $user->id
        ]);

        $participant = User::factory()->create();
        $session->participants()->attach($participant);

        $response = $this->actingAs($user)
            ->delete(route('group.sessions.destroy', $session));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Session canceled successfully.');
        $this->assertDatabaseMissing('sessions', ['id' => $session->id]);
    }

    public function test_non_leader_cannot_cancel_session()
    {
        $this->actingAs($user = User::factory()->create());
        $group = Group::factory()->create();
        $session = Session::factory()->create([
            'group_id' => $group->id,
            'leader_id' => User::factory()->create()->id
        ]);

        $response = $this->actingAs($user)
            ->delete(route('group.sessions.destroy', $session));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Unauthorized action. Only the session leader can cancel this session.');
    }


    public function test_cannot_cancel_past_session()
    {
        $this->actingAs($user = User::factory()->create());
        $group = Group::factory()->create();
        $session = Session::factory()->create([
            'group_id' => $group->id,
            'leader_id' => $user->id,
            'date_time' => now()->subDay() // Past date
        ]);

        $response = $this->actingAs($user)
            ->delete(route('group.sessions.destroy', $session));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot cancel past sessions.');
    }
}
