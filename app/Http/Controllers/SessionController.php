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
    
        // // Debugging: Check if sessions are retrieved
        // if ($sessions->isEmpty()) {
        //     logger('No sessions found for group_id: ' . $group->id);
        // } else {
        //     logger('Sessions retrieved: ', $sessions->toArray());
        // }
    
        return Inertia::render('Employee/Group/Show', [
            'group' => $group,
            'sessions' => $sessions,
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
            'group_id' => 'required|exists:groups,id',
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

    
}
