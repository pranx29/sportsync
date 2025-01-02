<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Notifications\EventUpdatedNotification;
use Illuminate\Support\Facades\Notification;

class EventController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Event/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Event/Create');
    }

    public function store(Request $request, Event $event, User $user)
    {
        $validated = $request->validate([
            'eventName' => 'required|string|min:2',
            'eventDescription' => 'required|string|min:2',
            'sportType' => 'required|string|min:2',
            'eventImage' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'registrationType' => 'required|string',
            'maxParticipants' => 'required|integer|min:1',
            'registrationDeadline' => 'nullable|date',
            'numberOfTeams' => 'nullable|integer|min:1',
            'teamAssignment' => 'nullable|string',
            'venue' => 'nullable|string',
            'customLocationName' => 'nullable|required_if:customLocation,true|string',
            'customLocationLink' => 'nullable|required_if:customLocation,true|url',
            'locationType' => 'required|in:indoor,outdoor',
            'eventDate' => 'required|date',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
            'rulesDescription' => 'required|string|min:10',
            'rulesDocument' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
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

        $event = Event::create($validated);

        // Notify employees if notifyCreation is true
        if ($request->has('notifyCreation') && $request->notifyCreation) {
            $employees = User::where('role', User::EMPLOYEE)->get();
            Notification::send($employees, new EventUpdatedNotification($event, 'created'));
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }
}