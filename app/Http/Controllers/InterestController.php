<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Sport;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class InterestController extends Controller
{
    /**
     * Display the interest view.
     */
    public function create(Request $request): Response
    {
        $sports = Sport::select('id', 'name')->get();
        return Inertia::render('Profile/Partials/SetInterestForm', ['sports' => $sports]);
    }

    /**
     * Store the sport interests details.
     */

    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());
        try {

            // Store multiple sports
            if (isset($request->sports) || !empty($request->sports)) {
                // Validate the request
                $request->validate([
                    'sports' => 'array',
                    'sports.*' => 'exists:' . Sport::class . ',name',
                ]);

                // Convert the sports to ids
                $request->sports = Sport::whereIn('name', $request->sports)->pluck('id')->toArray();
                // Attach the sports to the user
                $request->user()->sports()->sync($request->sports);
                // Return with success message
                return redirect()->route('create.availability');
            } else {
                // Store single sport
                $validated = $request->validate([
                    'sport_id' => 'required|exists:' . Sport::class . ',id',
                    'interest_level' => 'in:low,medium,high',
                    'skill_level' => 'in:beginner,intermediate,advanced',
                ]);

                // Attach the sport to the user
                $request->user()->sports()->attach($validated['sport_id'], [
                    'interest_level' => $validated['interest_level'],
                    'skill_level' => $validated['skill_level'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                // Return with success message to the profile page
                return redirect()->route('profile.show')->with('success', 'Interests added successfully');
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the interest.
     */

    public function update(Request $request): RedirectResponse
    {
        try {
            // Validate the request
            $request->validate([
                'sport_id' => 'required|exists:' . Sport::class . ',id',
                'interest_level' => 'in:low,medium,high',
                'skill_level' => 'in:beginner,intermediate,advanced',
            ]);

            // Update the user's sports ishownterest level and skill level
            $request->user()->sports()->updateExistingPivot($request->sport_id, [
                'interest_level' => $request->interest_level,
                'skill_level' => $request->skill_level,
            ]);



        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        // Return with success message
        return redirect()->route('profile.show')->with('success', 'Interests added successfully');
    }
}
