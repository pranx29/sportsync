<?php
use App\Models\Session;
use App\Models\User;
use App\Notifications\ParticipantUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeaveSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_leader_cannot_leave()
    {
        $leader = User::factory()->create();
        $session = Session::factory()->create(['leader_id' => $leader->id]);

        $response = $this->actingAs($leader)->post(route('group.sessions.leave', $session));

        $response->assertSessionHas('error', 'Session leaders cannot leave.');
    }

    public function test_cannot_leave_if_not_participant()
    {
        $user = User::factory()->create();
        $session = Session::factory()->create();

        $response = $this->actingAs($user)->post(route('group.sessions.leave', $session));

        $response->assertSessionHas('error', 'You have not joined this session.');
    }

    public function test_cannot_leave_past_session()
    {
        $user = User::factory()->create();
        $session = Session::factory()->create([
            'date_time' => now()->subDay()
        ]);
        $session->participants()->attach($user->id);

        $response = $this->actingAs($user)->post(route('group.sessions.leave', $session));

        $response->assertSessionHas('error', 'Cannot leave past sessions.');
        $this->assertTrue($session->participants()->where('user_id', $user->id)->exists());
    }

    public function test_can_leave_session_successfully()
    {
        Notification::fake();

        $user = User::factory()->create();
        $session = Session::factory()->create([
            'date_time' => now()->addDay()
        ]);
        $session->participants()->attach($user->id);

        $response = $this->actingAs($user)->post(route('group.sessions.leave', $session));

        $response->assertSessionHas('success', 'Successfully left the session.');
        $this->assertFalse($session->participants()->where('user_id', $user->id)->exists());
        Notification::assertSentTo(
            $session->leader,
            ParticipantUpdatedNotification::class
        );
    }
}
