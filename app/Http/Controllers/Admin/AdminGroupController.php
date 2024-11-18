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
    public function index()
    {
        $groups = Group::withCount('users')->get();
        $sportWithoutGroups = Sport::whereDoesntHave('group')->get();

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


}
