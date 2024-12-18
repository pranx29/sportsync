<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\SessionCreated;
use App\Models\Message;
use Inertia\Inertia;
use App\Models\Group;
use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SessionUpdatedNotification;
use App\Notifications\ParticipantUpdatedNotification;

class SessionController extends Controller
{

    public function store(Request $request, Group $group)
    {
        if (!$group->users->contains(auth()->id()) && $group->leader_id === auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action. You must be a group member or leader to create a session.');
        }

        $request->validate([
            'session_name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:5',
            'participation_limit' => 'required|integer|min:1',
            'equipment_provided' => 'required|in:yes,no',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $session = Session::create([
            'group_id' => $request->group_id,
            'session_name' => $request->session_name,
            'date_time' => $request->date_time,
            'duration' => $request->duration,
            'participation_limit' => $request->participation_limit,
            'equipment_provided' => $request->equipment_provided === 'yes',
            'location' => $request->location,
            'description' => $request->description,
            'leader_id' => auth()->id(),
        ]);

        // Notify the group members via chat message
        $message = Message::create([
            'group_id' => $request->group_id,
            'user_id' => auth()->id(),
            'message' => auth()->user()->first_name . ' ' . auth()->user()->last_name . ' created a session: ' . $session->session_name,
            'type' => 'notification',
        ]);

        // Notify the group members via broadcast in chat
        broadcast(new MessageSent($message));

        $session->participants()->attach(auth()->id());
        return redirect()->back()->with([
            'success' => 'Session created successfully.',
        ]);
    }


    public function join(Session $session)
    {
        if ($session->participants()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You have already joined this session.');
        }

        $totalParticipants = $session->participants->count();
        if ($totalParticipants === $session->participation_limit) {
            return redirect()->back()->with('error', 'Session is full.');
        }

        // Add authenticated user to the participants list
        $session->participants()->attach(auth()->id());

        // Notify the session leader
        $participant = auth()->user();
        $session->leader->notify(new ParticipantUpdatedNotification($session, $participant, 'joined'));
        return redirect()->back()->with('success', 'Successfully joined the session.');
    }

    public function leave(Session $session)
    {
        if ($session->leader_id === auth()->id()) {
            return back()->with('error', 'Session leaders cannot leave.');
        }

        if (!$session->participants()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You have not joined this session.');
        }

        if ($session->date_time < now()) {
            return redirect()->back()->with('error', 'Cannot leave past sessions.');
        }

        $session->participants()->detach(auth()->id());

        // Notify the session leader

        $participant = auth()->user();
        $session->leader->notify(new ParticipantUpdatedNotification($session, $participant, 'left'));
        redirect()->back()->with('success', 'Successfully left the session.');
    }

    public function update(Request $request, Session $session)
    {
        if ($session->leader_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action. Only the session leader can update this session.');
        }

        $request->validate([
            'session_name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:5',
            'participation_limit' => 'required|integer|min:1',
            'equipment_provided' => 'required|in:yes,no',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $session->update([
            'session_name' => $request->session_name,
            'date_time' => $request->date_time,
            'duration' => $request->duration,
            'participation_limit' => $request->participation_limit,
            'equipment_provided' => $request->equipment_provided === 'yes',
            'location' => $request->location,
            'description' => $request->description,
        ]);

        // Notify participants
        Notification::send($session->participants, new SessionUpdatedNotification($session, 'updated'));

        return redirect()->back()->with('success', 'Session created successfully.');
    }

    public function destroy(Session $session)
    {
        if ($session->leader_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action. Only the session leader can cancel this session.');
        }
        
        // Check if the session is past the current date
        if ($session->date_time < now()) {
            return redirect()->back()->with('error', 'Cannot cancel past sessions.');
        }

        // Notify participants
        Notification::send($session->participants, new SessionUpdatedNotification($session, 'cancelled'));

        $session->delete();
        return redirect()->back()->with('success', 'Session canceled successfully.');
    }

}
