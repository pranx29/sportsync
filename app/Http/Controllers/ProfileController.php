<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Role;
use Inertia\Inertia;
use App\Models\Group;
use App\Models\Sport;
use Inertia\Response;
use App\Models\Profile;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the about view.
     */
    public function create(Request $request): Response
    {
        // If user has not created a profile, return the about form
        $jobs = Role::select('id', 'title')->get();
        $departments = Department::select('id', 'name')->get();
        return Inertia::render(
            'Employee/Profile/Partials/CreateAboutForm',
            [
                'jobs' => $jobs,
                'departments' => $departments,
            ]
        );
    }

    /**
     * Store the about details.
     */

    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->is_profile_created) {
            return Redirect::route('create.interest');
        }
        try {
            // Validate the request
            $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'nullable|string|max:255',
                'dob' => 'required|date',
                'jobTitle' => 'required|exists:' . Role::class . ',title',
                'department' => 'required|exists:' . Department::class . ',name',
                'picture' => 'image|max:2048',
            ]);

            // if picture is uploaded
            if ($request->hasFile('picture')) {
                $profile_image = $request->file('picture')->store('profile_pictures', 'public');
            }

            // Create the profile for the employee
            Profile::create([
                'user_id' => Auth::id(),
                'department_id' => Department::where('name', $request->department)->first()->id,
                'role_id' => Role::where('title', $request->jobTitle)->first()->id,
                'gender' => $request->gender,
                'profile_image' => isset($profile_image) ? $profile_image : null,
                'date_of_birth' => $request->dob,
            ]);

            // Update the user's profile status to created
            Auth::user()->update(['is_profile_created' => true]);

            // Redirect to the next step (Set Interest)
            sleep(1);
            return redirect()->route('create.interest');

        } catch (\Exception $e) {
            return Redirect::route('create.about')->with('error', 'Failed to create profile.');
        }
    }

    /**
     * Display the user's profile.
     */

    public function show(Request $request): Response
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        $interests = Auth::user()->sports()->get(['id', 'name', 'interest_level', 'skill_level']);
        $sports = Sport::select('id', 'name')->get();
        $sports = $sports->diff($interests);
        $joinedGroups = Group::where('is_active', true)
            ->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orWhere('user_id', auth()->id())->get();


        return Inertia::render('Employee/Profile/View', [
            'profile' => $profile->load('department', 'role'),
            'interests' => $interests,
            'sports' => $sports,
            'joinedGroups' => $joinedGroups,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $profile = Profile::where('user_id', Auth::id())->first()->load('department', 'role');
        $jobs = Role::select('id', 'title')->get();
        $departments = Department::select('id', 'name')->get();

        return Inertia::render('Employee/Settings/Profile', [
            'profile' => $profile,
            'jobs' => $jobs,
            'departments' => $departments,

        ]);

    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            // Update the user's names
            $request->user()->update([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
            ]);

            // Update the user's profile
            $profile = Profile::where('user_id', Auth::id())->first();

            // if picture is uploaded
            if ($request->hasFile('picture')) {
                $profile_image = $request->file('picture')->store('profile_pictures', 'public');
                $profile->update(['profile_image' => $profile_image]);
            }

            // If picture is removed
            if ($request->removePicture) {
                Storage::disk('public')->delete($profile->profile_image);
                $profile->update(['profile_image' => null]);
            }

            $profile->update([
                'department_id' => $request->department,
                'role_id' => $request->jobTitle,
                'date_of_birth' => $request->dateOfBirth,
            ]);
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')->with('error', 'Failed to update profile.');
        }
        return Redirect::route('profile.edit')->with('success', 'Profile updated.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
