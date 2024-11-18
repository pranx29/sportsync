<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        // Get all groups which are active and not joined by the user
        $groups = Group::where('is_active', true)
            ->whereDoesntHave('users', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->get();

        // Get all joined groups
        $joinedGroups = auth()->user()->groups;

        return Inertia::render(
            'Employee/Group/Index',
            [
                'groups' => $groups,
                'joinedGroups' => $joinedGroups
            ]
        );
    }

    public function joinGroup(Request $request)
    {
        $group = Group::find($request->group_id);
        $group->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        sleep(1);
        return redirect()->route('employee.groups')->with('success', 'Group joined successfully');
    }

    public function show(Group $group)
    {
        $leader = $group->leader;
        $members = $group->users;
        return Inertia::render('Employee/Group/Show', ['group' => $group,
            'leader' => $leader,
            'members' => $members]);
    }
}
