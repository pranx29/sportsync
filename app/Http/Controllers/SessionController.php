<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Session;
use App\Models\Group;
use App\Models\Feedback;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function show(Group $group)
    {
        $sessions = Session::where('group_id', $group->id)
                           ->with('participants')
                           ->get();
    
        $now = Carbon::now();
    
        $joinedSessions = $sessions->filter(function ($session) use ($now) {
            $sessionEnd = Carbon::parse($session->date_time)->addMinutes($session->duration);
            return $session->participants->contains('id', auth()->id()) && $now->lessThan($sessionEnd);
        });
    
        $pastSessions = $sessions->filter(function ($session) use ($now) {
            $sessionEnd = Carbon::parse($session->date_time)->addMinutes($session->duration);
            return $now->greaterThanOrEqualTo($sessionEnd);
        });
    
        $availableSessions = $sessions->filter(function ($session) use ($now) {
            $sessionEnd = Carbon::parse($session->date_time)->addMinutes($session->duration);
            return !$session->participants->contains('id', auth()->id()) &&
                   $now->lessThan($sessionEnd) &&
                   $session->participants->count() < $session->participation_limit; 
        });
    
        return Inertia::render('Employee/Group/Show', [
            'group' => $group,
            'availableSessions' => $availableSessions->values(),
            'joinedSessions' => $joinedSessions->values(),
            'pastSessions' => $pastSessions->values(),
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
            'duration' => 'required|integer|min:1',
            'participation_limit' => 'required|integer|min:1',
            'equipment_provided' => 'required|in:yes,no',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
    
        $startDateTime = Carbon::parse($request->date_time);
        $endDateTime = $startDateTime->copy()->addMinutes($request->duration);
    
        $session = Session::create([
            'group_id' => $request->group_id,
            'session_name' => $request->session_name,
            'date_time' => $startDateTime,
            'end_date_time' => $endDateTime,
            'duration' => $request->duration,
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
        $now = Carbon::now();
        $sessionEndTime = Carbon::parse($session->date_time)->addMinutes($session->duration);
    
        if ($session->participants()->where('user_id', auth()->id())->exists()) {
            return response()->json(['message' => 'You are already a participant.'], 400);
        }
    
        if ($session->participants->count() >= $session->participation_limit || $now->greaterThan($sessionEndTime)) {
            return response()->json(['message' => 'Session is no longer available.'], 400);
        }
    
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
            'duration' => 'required|integer|min:1',
            'participation_limit' => 'required|integer|min:1',
            'equipment_provided' => 'required|in:yes,no',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $startDateTime = Carbon::parse($request->date_time);
        $endDateTime = $startDateTime->copy()->addMinutes($request->duration);
    
        $session->update([
            'session_name' => $request->session_name,
            'date_time' => $startDateTime,
            'end_date_time' => $endDateTime,
            'duration' => $request->duration,
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

    public function storeFeedback(Request $request, Session $session)
    {
        $request->validate([
            'comments' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Feedback::create([
            'session_id' => $session->id,
            'user_id' => auth()->id(),
            'comments' => $request->comments,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Feedback submitted successfully.');
    }

    public function showFeedback(Session $session)
    {
        $feedbacks = $session->feedbacks()->with('user')->get();
        return response()->json($feedbacks);
    }

}
