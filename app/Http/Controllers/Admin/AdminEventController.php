<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sport;
use App\Models\Venue;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Notifications\EventUpdatedNotification;
use Illuminate\Support\Facades\Notification;

class AdminEventController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Event/Index', [
            'events' => Event::with('sport', 'venue')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Event/Create', [
            'sports' => Sport::all(),
            'venues' => Venue::all(),
        ]);
    }

    public function store(Request $request, Event $event, User $user)
    {
        $validated = $request->validate([
            // Event basics
            'eventName' => 'required|string|min:2',
            'eventDescription' => 'required|string|min:2',
            'sportType' => 'required|string|min:2',
            'eventImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Registration setup
            'registrationType' => 'required|string|min:2',
            'maxParticipants' => 'required|integer|min:1',
            'registrationDeadline' => 'required|date',
            'numberOfTeams' => 'nullable|integer|min:1',
            'teamAssignment' => 'nullable|string',

            // Location and schedule
            'venue' => 'nullable|string',
            'customLocation' => 'nullable|boolean',
            'customLocationName' => 'nullable|string',
            'customLocationLink' => 'nullable|url',
            'locationType' => 'required|in:indoor,outdoor',
            'eventDate' => 'required|date',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',

            // Rules and notifications
            'rulesDocument' => 'required|file|mimes:pdf,doc,docx',
            'rulesDescription' => 'required|string|min:10',
            'notifyCreation' => 'nullable|boolean',
            'sendReminder' => 'nullable|boolean',
            'notifyAssignments' => 'nullable|boolean',
        ]);

        // Handle file uploads
        if ($request->hasFile('eventImage')) {
            $validated['eventImage'] = $request->file('eventImage')->store('event_images', 'public');
        }

        if ($request->hasFile('rulesDocument')) {
            $validated['rulesDocument'] = $request->file('rulesDocument')->store('rules_documents', 'public');
        }

        // Handle location
        if ($request->has('customLocation') && $request->customLocation) {
            $validated['venue'] = null;
        } else {
            $validated['customLocationName'] = null;
            $validated['customLocationLink'] = null;
        }

        // Handle team
        if ($request->registrationType === 'individual') {
            $validated['numberOfTeams'] = null;
            $validated['teamAssignment'] = null;
        }

        // Save the event to the database
        $event = Event::create([
            'name' => $validated['eventName'],
            'description' => $validated['eventDescription'],
            'sport_id' => Sport::where('name', $validated['sportType'])->first()->id,
            'image' => $validated['eventImage'] ?? null,
            'registration_type' => $validated['registrationType'],
            'max_participants' => $validated['maxParticipants'],
            'registration_deadline' => $validated['registrationDeadline'] ?? null,
            'number_of_teams' => $validated['numberOfTeams'] ?? null,
            'team_assignment' => $validated['teamAssignment'] ?? null,
            'venue_id' => $validated['venue'] ? Venue::where('name', $validated['venue'])->first()->id : null,
            'custom_location_name' => $validated['customLocationName'] ?? null,
            'custom_location_link' => $validated['customLocationLink'] ?? null,
            'location_type' => $validated['locationType'],
            'event_date' => $validated['eventDate'],
            'start_time' => $validated['startTime'],
            'end_time' => $validated['endTime'],
            'rules_description' => $validated['rulesDescription'],
            'rules_document' => $validated['rulesDocument'] ?? null,
            'notify_creation' => $validated['notifyCreation'] ?? false,
            'send_reminder' => $validated['sendReminder'] ?? false,
            'notify_assignments' => $validated['notifyAssignments'] ?? false,
            'status' => Event::STATUS_UPCOMING,
        ]);

        // Notify employees if notifyCreation is true
        if ($request->has('notifyCreation') && $request->notifyCreation) {
            $employees = User::where('role', User::EMPLOYEE)->get();
            Notification::send($employees, new EventUpdatedNotification($event, 'created'));
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return Inertia::render('Admin/Event/Show', [
            'event' => $event->load('sport', 'venue'),
            'venues' => Venue::all(),
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'eventName' => 'required|string|min:2',
            'eventDescription' => 'required|string|min:2',
            'maxParticipants' => 'required|integer|min:1',
            'eventDate' => 'required|date|after:today',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
            'customLocationName' => 'nullable|string',
            'customLocationLink' => 'nullable|url',
            'registrationDeadline' => 'required|date|after:today',
            'rulesDescription' => 'required|string|min:10',
        ]);

        // if ($request->hasFile('eventImage')) {
        //     $validated['eventImage'] = $request->file('eventImage')->store('event_images', 'public');
        // }

        $event->update([
            'name' => $validated['eventName'],
            'description' => $validated['eventDescription'],
            'max_participants' => $validated['maxParticipants'],
            'event_date' => $validated['eventDate'],
            'start_time' => $validated['startTime'],
            'end_time' => $validated['endTime'],
            'custom_location_name' => $validated['customLocationName'] ?? $event->custom_location_name,
            'custom_location_link' => $validated['customLocationLink'] ?? $event->custom_location_link,
            'registration_deadline' => $validated['registrationDeadline'],
            'rules_description' => $validated['rulesDescription'],
        ]);

        return redirect()->back()->with('success', 'Event updated successfully!');
    }

    public function cancel(Event $event)
    {
        $event->update(['status' => Event::STATUS_CANCELED]);

        return redirect()->route('admin.events.index')->with('success', 'Event status updated to canceled successfully!');
    }
}
