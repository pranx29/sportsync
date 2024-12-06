<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Session;
use App\Models\Group;

class SessionController extends Controller
{

    public function show(Group $group)
    {
        $sessions = Session::where('group_id', $group->id)
                           ->with('participants')
                           ->get();
    
        $joinedSessions = $group->sessions()->whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();
    
        return Inertia::render('Employee/Group/Show', [
            'group' => $group,
            'sessions' => $sessions,
            'joinedSessions' => $joinedSessions,
            'leader' => $group->leader,
            'members' => $group->users,
        ]);
    }
    

    public function store(Request $request, Group $group)
    {
        if (!$group->users->contains(auth()->id())) {
            abort(403, 'Unauthorized action. Only group members can create sessions.');
        }

        $request->validate([
            'session_name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'participation_limit' => 'required|integer|min:1',
            'equipment_provided' => 'required|in:yes,no',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $session = Session::create([
            'group_id' => $request->group_id,
            'session_name' => $request->session_name,
            'date_time' => $request->date_time,
            'participation_limit' => $request->participation_limit,
            'equipment_provided' => $request->equipment_provided === 'yes',
            'location' => $request->location,
            'description' => $request->description,
            'leader_id' => auth()->id(),
        ]);

        $session->participants()->attach(auth()->id());

        return redirect()->route('employee.groups.show', ['group' => $request->group_id])
            ->with('success', 'Session created successfully.');
    }


    public function join(Session $session)
    {
        if ($session->participants()->where('user_id', auth()->id())->exists()) {
            return response()->json(['message' => 'You are already a participant.'], 400);
        }
    
        $totalParticipants = $session->participants->count();
        if ($totalParticipants >= $session->participation_limit) {
            return response()->json(['message' => 'Session is full.'], 400);
        }
    
        // Add authenticated user to the participants list
        $session->participants()->attach(auth()->id());
    
        return response()->json(['message' => 'Successfully joined the session.', 'session' => $session->load('participants')]);
    }

    public function leave(Session $session)
    {
        if ($session->leader_id === auth()->id()) {
            return back()->with('error', 'Session leaders cannot leave.');
        }

        $session->participants()->detach(auth()->id());

        return back()->with('success', 'Successfully left the session.');
    }

    public function update(Request $request, Session $session)
    {
        if ($session->leader_id !== auth()->id()) {
            abort(403, 'Unauthorized action. Only the session leader can edit this session.');
        }
    
        $request->validate([
            'session_name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'participation_limit' => 'required|integer|min:1',
            'equipment_provided' => 'required|in:yes,no',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
    
        $session->update([
            'session_name' => $request->session_name,
            'date_time' => $request->date_time,
            'participation_limit' => $request->participation_limit,
            'equipment_provided' => $request->equipment_provided === 'yes',
            'location' => $request->location,
            'description' => $request->description,
        ]);
    
        return redirect()->route('employee.groups.show', ['group' => $session->group_id])
            ->with('success', 'Session updated successfully.');
    }

    public function destroy(Session $session)
    {
        if ($session->leader_id !== auth()->id()) {
            abort(403, 'Unauthorized action. Only the session leader can cancel this session.');
        }

        $session->delete();

        return redirect()->route('employee.groups.show', ['group' => $session->group_id])
            ->with('success', 'Session canceled successfully.');
    }

}
