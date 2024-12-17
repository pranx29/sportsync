<?php
use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JoinSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_join_session()
    {
        $this->actingAs($user = User::factory()->create());
        $group = Group::factory()->create();
        $session = Session::factory()->create(['group_id' => $group->id]);

        // Add user to the group
        $group->users()->attach($user->id);

        $response = $this->post(route('group.sessions.join', $session));

        $response->assertRedirect()->with('success', 'Successfully joined the session.');
    }


    public function test_user_cannot_join_session_twice()
    {
        $this->actingAs($user = User::factory()->create());
        $group = Group::factory()->create();
        $session = Session::factory()->create(['group_id' => $group->id]);

        // Add user to the group and session
        $group->users()->attach($user->id);
        $session->participants()->attach($user->id);

        $response = $this->post(route('group.sessions.join', $session));

        $response->assertRedirect()->with('error', 'You have already joined this session.');
    }

    public function test_user_cannot_join_full_session()
    {
        $this->actingAs($user = User::factory()->create());
        $group = Group::factory()->create();
        $session = Session::factory()->create(['group_id' => $group->id, 'participation_limit' => 1]);

        // Add user to the group
        $group->users()->attach($user->id);

        // Add another user to the session
        $session->participants()->attach(User::factory()->create()->id);

        $response = $this->post(route('group.sessions.join', $session));

        $response->assertRedirect()->with('error', 'Session is full.');
    }

}
