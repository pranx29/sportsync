<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
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
        sleep(1);
        return Inertia::render(
            'Profile/Partials/CreateAboutForm',
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
        try {
            // Validate the request
            $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'nullable|string|max:255',
                'dob' => 'required|date',
                'jobTitle' => 'required|exists:' . Role::class . ',title',
                'department' => 'required|exists:' . Department::class . ',name',
            ]);

            // Create the profile for the employee
            Profile::create([
                'user_id' => Auth::id(),
                'department_id' => Department::where('name', $request->department)->first()->id,
                'role_id' => Role::where('title', $request->jobTitle)->first()->id,
                'gender' => $request->gender,
                'profile_image' => $request->profile_image,
                'date_of_birth' => $request->dob,
            ]);

            // Redirect to the next step (Set Interest)
            sleep(1);
            return redirect()->route('create.interest');

        } catch (\Exception $e) {
            dd($e->getMessage());
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

        return Inertia::render('Profile/ViewProfile', [
            'profile' => $profile->load('department', 'role'),
            'interests' => $interests,
            'sports' => $sports,
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
        return Inertia::render('Profile/Edit', [
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
            $profile->update([
            'department_id' => $request->department,
            'role_id' => $request->jobTitle,
            'date_of_birth' => $request->dateOfBirth,
            ]);
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')->with('error', 'Failed to update profile.');
        }
        // if ($request->user()->isDirty('email')) {
        //     $request->user()->email_verified_at = null;
        // }

        // $request->user()->save();

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
