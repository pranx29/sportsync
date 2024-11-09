<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class AvailabilityController extends Controller
{
    /**
     * Display the about view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render(
            'Profile/Partials/SetAvailabilityForm',
            [
                'timeslots' => Availability::TIME_SLOTS,
                'days' => Availability::DAYS,
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
                'days' => 'required|array',
                'days.*' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'timeslot' => 'required|in:6-9,9-12,12-15,15-18,18-21,21-24',
            ]);

            // Foreach day, create an availability record
            foreach ($request->days as $day) {
                Availability::create([
                    'user_id' => Auth::id(),
                    'day' => $day,
                    'time_slot' => $request->timeslot,
                ]);
            }

            sleep(1);
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


}
