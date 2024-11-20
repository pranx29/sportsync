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
            'Employee/Profile/Partials/SetAvailabilityForm',
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
            // return redirect()->route('dashboard');
            return Redirect::route('employee.groups');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Display the edit view for availability.
     */

    public function edit(Request $request): Response
    {
        $availability = Availability::where('user_id', Auth::id())->get();
        return Inertia::render(
            'Employee/Settings/Availability',
            [
                'timeslots' => Availability::TIME_SLOTS,
                'days' => Availability::DAYS,
                'availability' => $availability,
            ]
        );
    }

    /**
     * Update the availability details.
     */

    public function update(Request $request): RedirectResponse
    {
        try {
            // Delete all availability records for the user
            Availability::where('user_id', Auth::id())->delete();

            // Foreach day, create an availability record which time slot is not null
            foreach ($request->all() as $day) {
                if (!empty($day['time_slot'])) {
                    Availability::create([
                        'user_id' => Auth::id(),
                        'day' => $day['day'],
                        'time_slot' => $day['time_slot'],
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Availability updated successfully');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


}
