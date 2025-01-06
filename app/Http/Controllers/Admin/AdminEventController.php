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
use Illuminate\Support\Facades\Log;

class AdminEventController extends Controller
{
    public function index()
    {
        $now = now()->setTimezone('UTC');
        
        $events = Event::all()->map(function ($event) use ($now) {
            if ($event->status === 'Cancelled') {
                $status = 'Cancelled';
            } else {

                Log::info('Event Date: ' . $event->eventDate);
                Log::info('Event End Time: ' . $event->endTime);
    
                try {
                    // Extract only the date part (Y-m-d) from the eventDate
                    $dateOnly = \Carbon\Carbon::parse($event->eventDate)->format('Y-m-d'); // Get only date
                    
                    Log::info('Formatted Date: ' . $dateOnly);
                
                    // Combine date with end time
                    $completionDateTime = \Carbon\Carbon::createFromFormat(
                        'Y-m-d H:i',
                        $dateOnly . ' ' . $event->endTime,
                        'UTC'
                    );
                
                    Log::info('Completion DateTime: ' . $completionDateTime);
                
                } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                    Log::error('Invalid date/time format: ' . $e->getMessage());
                }
    
                if ($now->greaterThanOrEqualTo($completionDateTime)) {
                    $status = 'Completed';
                    if ($event->status !== 'Completed') {
                        $event->update(['status' => 'Completed']);
                    }
                } else {
                    $status = 'Upcoming';
                    if ($event->status !== 'Upcoming') {
                        $event->update(['status' => 'Upcoming']);
                    }
                }
            }
    
            return [
                'id' => $event->id,
                'eventName' => $event->eventName,
                'eventDescription' => $event->eventDescription,
                'sportType' => $event->sportType,
                'registrationType' => $event->registrationType,
                'venue' => $event->venue,
                'customLocationName' => $event->customLocationName,
                'customLocationLink' => $event->customLocationLink,
                'eventDate' => $event->eventDate,
                'startTime' => $event->startTime,
                'endTime' => $event->endTime,
                'status' => $status,
                'created_at' => $event->created_at->format('Y-m-d'),
                'eventImage' => $event->eventImage 
                    ? Storage::url($event->eventImage) 
                    : '/placeholder.svg',
                'maxParticipants' => $event->maxParticipants,
                'registrationDeadline' => $event->registrationDeadline,
                'numberOfTeams' => $event->numberOfTeams,
                'rulesDescription' => $event->rulesDescription,
                'notificationsEnabled' => $event->notifyCreation,
            ];
        });
    
        return Inertia::render('Admin/Event/Index', [
            'events' => $events,
        ]);
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

        if ($request->has('notifyCreation') && $request->notifyCreation) {
            $employees = User::where('role', User::EMPLOYEE)->get();
            Notification::send($employees, new EventUpdatedNotification($event, 'created'));
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    public function cancel(Request $request, Event $event)
    {
        // Check if the event is already cancelled
        if ($event->status === 'Cancelled') {
            return back()->withErrors(['error' => 'This event is already cancelled.']);
        }
    
        if (now()->greaterThanOrEqualTo($event->eventDate)) {
            Log::error('Event already started or is in the past.');
            return back()->withErrors(['error' => 'Event has already started or is in the past and cannot be cancelled.']);
        }
    
        $daysBeforeEvent = now()->diffInDays($event->eventDate, false);
        if ($daysBeforeEvent < 2) {
            return back()->withErrors(['error' => 'Event can only be cancelled at least 2 days before the event date.']);
        }
    
        $event->update([
            'status' => 'Cancelled',
            'cancelled_at' => now(),
        ]);
    
        $employees = User::where('role', User::EMPLOYEE)->get();
        Notification::send($employees, new EventUpdatedNotification($event, 'cancelled'));
    
        return redirect()->route('admin.events.index')->with('success', 'Event cancelled successfully!');
    }

    public function show(Event $event)
    {
        return Inertia::render('Admin/Event/Show', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event)
    {

        $validated = $request->validate([
            'eventName' => 'required|string|min:2',
            'eventDescription' => 'required|string|min:2',
            'sportType' => 'required|string|min:2',
            'eventImage' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'maxParticipants' => 'required|integer|min:1',
            'registrationDeadline' => 'nullable|date',
            'numberOfTeams' => 'nullable|integer|min:1',
            'venue' => 'nullable|string',
            'customLocationName' => 'nullable|string',
            'customLocationLink' => 'nullable|url',
            'eventDate' => 'required|date',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
            'rulesDescription' => 'required|string|min:10',
        ]);

        $event->update($validated);

        $employees = User::where('role', User::EMPLOYEE)->get();
        Notification::send($employees, new EventUpdatedNotification($event, 'updated'));

        return redirect()->route('admin.events.show')->with('success', 'Event updated successfully!');
    }
}