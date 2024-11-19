<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Group;
use App\Models\Sport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminGroupController extends Controller
{
    public function index(Request $request)
    {

        $sportWithoutGroups = Sport::whereDoesntHave('group')->get();


        // Get all groups that are is_active is false
        if ($request->has('active')) {
            $groups = Group::where('is_active', $request->active)->withCount('users')->get();
        } else {
            // Get all groups
            $groups = Group::withCount('users')->get();
        }

        // For each group, add the leader object
        $groups->each(function ($group) {
            $group->leader;
        });

        return Inertia::render(
            'Admin/Group/Index',
            [
                'groups' => $groups->load(['sport', 'users']),
                'sportWithoutGroups' => $sportWithoutGroups
            ]
        );

    }

    public function store(Request $request)
    {

        $request->validate([
            'group_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'sport_id' => 'required|exists:sports,id',
            'leader' => 'required|exists:users,email',
            'image' => 'image',
        ]);

        $request->leader = User::where('email', $request->leader)->first()->id;

        // if picture is uploaded
        if ($request->hasFile('image')) {
            $group_image = $request->file('image')->store('group_images', 'public');
        }

        Group::create(
            [
                'name' => $request->group_name,
                'description' => $request->description,
                'sport_id' => $request->sport_id,
                'user_id' => $request->leader,
                'image' => $group_image,
            ]
        );

        return redirect()->route('admin.groups.index')->with('success', 'Group created successfully');
    }

    public function update(Request $request, $id)
    {
        $group = Group::find($id);
        $request->validate([
            'group_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'leader' => 'required|exists:users,email',
            'is_active' => 'required|in:0,1',
            'image' => 'image',
        ]);

        $request->leader = User::where('email', $request->leader)->first()->id;

        // if picture is uploaded
        if ($request->hasFile('image')) {
            $group_image = $request->file('image')->store('group_images', 'public');
            $group->update(
                [
                    'image' => $group_image,
                ]
            );
        }
        $group->update(
            [
                'name' => $request->group_name,
                'description' => $request->description,
                'user_id' => $request->leader,
                'is_active' => $request->is_active,
                'updated_at' => now(),
            ]
        );

        return redirect()->route('admin.groups.index')->with('success', 'Group updated successfully');
    }


}
