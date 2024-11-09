<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Sport;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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
        try {
            // Validate the request
            $request->validate([
                'sports' => 'required|array',
                'sports.*' => 'required|exists:' . Sport::class . ',name',
            ]);

            // Convert the sports to ids
            $request->sports = Sport::whereIn('name', $request->sports)->pluck('id')->toArray();

            // Attach the sports to the user
            $request->user()->sports()->sync($request->sports);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        // Redirect to next step ( Set Availability )
        sleep(1);
        return redirect()->route('create.availability');
    }
}
