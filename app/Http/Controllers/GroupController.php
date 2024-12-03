<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        // Get all groups which are active and not joined by the user and leader of the group
        $groups = Group::where('is_active', true)
            ->whereDoesntHave('users', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id())
            ->get();

        // Get all joined groups by the user and leader of the group
        $joinedGroups = Group::where('is_active', true)
            ->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orWhere('user_id', auth()->id())->get();

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
        return Inertia::render('Employee/Group/Show', [
            'group' => $group,
            'leader' => $group->leader->load('profile'),
            'members' => $group->users->load('profile'),
            'messages' => $group->messages()->with('user.profile')->get()
        ]);
    }

    public function leaveGroup(Request $request)
    {
        $group = Group::find($request->id);

        // Check if the user is the leader of the group
        if ($group->user_id == auth()->id()) {
            return redirect()->route('employee.groups')->with('error', 'You are the leader of the group');
        }

        $group->users()->detach(auth()->id());
        sleep(1);
        return redirect()->route('employee.groups')->with('success', 'Group left successfully');
    }
}
