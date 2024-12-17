<?php

use App\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class SessionFeedbackTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_submit_feedback_for_attended_session()
    {
        $user = User::factory()->create();
        $session = Session::factory()->create([
            'date_time' => now()->subDay()
        ]);
        $session->participants()->attach($user->id);
        $this->actingAs($user);

        $response = $this->post(route('group.sessions.feedback', $session->id), [
            'comment' => 'Great session!',
            'rating' => 5
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('session_feedback', [
            'session_id' => $session->id,
            'user_id' => $user->id,
            'comments' => 'Great session!',
            'rating' => 5
        ]);
    }

    public function test_user_cannot_submit_feedback_for_unattended_session()
    {
        $user = User::factory()->create();
        $session = Session::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('group.sessions.feedback', $session->id), [
            'comment' => 'Test comment',
            'rating' => 4
        ]);

        $response->assertSessionHas('error', 'You must join the session to submit feedback.');
    }

    public function test_user_cannot_submit_feedback_for_upcoming_session()
    {
        $user = User::factory()->create();
        $session = Session::factory()->create([
            'date_time' => now()->addDay()
        ]);
        $session->participants()->attach($user->id);
        $this->actingAs($user);

        $response = $this->post(route('group.sessions.feedback', $session->id), [
            'comment' => 'Test comment',
            'rating' => 4
        ]);

        $response->assertSessionHas('error', 'You cannot submit feedback for a upcoming session.');
    }

    public function test_user_cannot_submit_multiple_feedback()
    {
        $user = User::factory()->create();
        $session = Session::factory()->create([
            'date_time' => now()->subDay()
        ]);
        $session->participants()->attach($user->id);
        $this->actingAs($user);

        // First submission
        $this->post(route('group.sessions.feedback', $session->id), [
            'comment' => 'First feedback',
            'rating' => 5
        ]);

        // Second submission attempt
        $response = $this->post(route('group.sessions.feedback', $session->id), [
            'comment' => 'Second feedback',
            'rating' => 4
        ]);

        $response->content();

        $response->assertSessionHas('error', 'You have already submitted feedback for this session.');
    }
}
