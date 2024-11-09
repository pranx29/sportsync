<?php

namespace App\Http\Controllers\Auth;

use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class CreateEmployeeAboutController extends Controller
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
            $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'nullable|string|max:255',
                'dob' => 'required|date',
                'jobTitle' => 'required|exists:' . Role::class . ',title',
                'department' => 'required|exists:' . Department::class . ',name',
            ]);

            Profile::create([
                'user_id' => Auth::id(),
                'department_id' => Department::where('name', $request->department)->first()->id,
                'role_id' => Role::where('title', $request->jobTitle)->first()->id,
                'gender' => $request->gender,
                'profile_image' => $request->profile_image,
                'date_of_birth' => $request->dob,
            ]);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    /**
     * Display the interest view.
     */

    public function interest(): Response
    {
        return Inertia::render('Profile/Partials/SetInterestForm');
    }

    /**
     * Display the availability view.
     */
    public function availability(): Response
    {
        return Inertia::render('Profile/Partials/SetAvailabilityForm');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
}
