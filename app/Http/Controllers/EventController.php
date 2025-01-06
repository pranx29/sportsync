<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $now = now()->setTimezone('UTC');
        $user = auth()->id();
    
        $upcomingEvents = Event::where('status', 'Upcoming')
            ->whereDate('eventDate', '>=', $now->toDateString())
            ->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->orderBy('eventDate', 'asc')
            ->get()
            ->map(function ($event) {
            return [
                'id' => $event->id,
                'eventName' => $event->eventName,
                'eventDescription' => $event->eventDescription,
                'sportType' => $event->sportType,
                'registrationType' => $event->registrationType,
                'venue' => $event->venue,
                'customLocationName' => $event->customLocationName,
                'locationType' => $event->locationType,
                'eventDate' => $event->eventDate,
                'startTime' => $event->startTime,
                'endTime' => $event->endTime,
                'created_at' => $event->created_at->format('Y-m-d'),
                'eventImage' => $event->eventImage 
                    ? Storage::url($event->eventImage) 
                    : '/placeholder.svg',
                'maxParticipants' => $event->maxParticipants,
                'registrationDeadline' => $event->registrationDeadline,
                'numberOfTeams' => $event->numberOfTeams,
                'rulesDescription' => $event->rulesDescription,
                'notificationsEnabled' => $event->notifyCreation,
                'teams' => $event->users()->whereNotNull('team_name')->distinct('team_name')->pluck('team_name'),
            ];
            });
     
        $registeredEvents = Event::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user);
        })
        ->orderBy('eventDate', 'asc')
        ->get()
        ->map(function ($event) {
            return [
                'id' => $event->id,
                'eventName' => $event->eventName,
                'venue' => $event->venue,
                'eventDate' => $event->eventDate,
                'customLocationName' => $event->customLocationName,
                'locationType' => $event->locationType,
                'endTime' => $event->endTime,
                'teams' => $event->users()->whereNotNull('team_name')->distinct('team_name')->pluck('team_name'),
            ];
        });
    
        return Inertia::render('Employee/Event/Index', [
            'upcomingEvents' => $upcomingEvents,
            'registeredEvents' => $registeredEvents,
        ]);
    }

    public function show()
    {
        return Inertia::render('Employee/Event/Show');
    }

    public function join(Request $request, Event $event)
    {
        $request->validate([
            'team_name' => 'nullable|string|max:255',
            'selected_team' => 'nullable|string|max:255',
        ], [
            'team_name.required_without' => 'Please enter a team name or select a team.',
            'selected_team.required_without' => 'Please select a team or enter a team name.',
        ]);

        if ($request->team_name && $request->selected_team) {
            return back()->withErrors(['team_name' => 'Please fill only one field: Team Name or Select Team.']);
        }
    
        $teamName = $request->team_name ?: $request->selected_team;
    
        if ($event->users()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('employee.events.index')
                ->with('error', 'You are already registered for this event.');
        }
    
        if ($event->registrationType === 'individual') {
            if ($event->users()->count() >= $event->maxParticipants) {
                return redirect()->route('employee.events.index')
                    ->with('error', 'Event is full!');
            }
    
            $event->users()->attach(auth()->id(), [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            return redirect()->route('employee.events.index')
                ->with('success', 'Successfully joined the event.');
        }
    
        if ($event->registrationType === 'team') {
            $maxParticipantsPerTeam = intval($event->maxParticipants / $event->numberOfTeams);
    
            if ($teamName) {
                $existingTeams = $event->users()
                    ->whereNotNull('team_name')
                    ->distinct('team_name')
                    ->count();
            
                if ($existingTeams >= $event->numberOfTeams) {
                    return back()->withErrors(['team_name' => 'Maximum number of teams has been reached.']);
                }
            
                $teamMembersCount = $event->teamMembers($teamName)->count();
                if ($teamMembersCount >= $maxParticipantsPerTeam) {
                    return back()->withErrors(['selected_team' => 'The selected team is full.']);
                }
            } else {
                $teamMembersCount = $event->teamMembers($teamName)->count();
                if ($teamMembersCount >= $maxParticipantsPerTeam) {
                    return back()->withErrors(['selected_team' => 'The selected team is full.']);
                }
            }
    
            $event->users()->attach(auth()->id(), [
                'team_name' => $teamName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            return redirect()->route('employee.events.index')
                ->with('success', 'Successfully joined the event.');
        }
    
        return redirect()->route('employee.events.index')
            ->with('error', 'Invalid registration type for this event.');
    }


    public function leave(Request $request, $event)
    {
        $event = Event::find($event);

        $event->users()->detach(auth()->id());

        return redirect()->route('employee.events.index')
                         ->with('success', 'Successfully left the event.');
    }

    public function viewEventDetails(Event $event)
    {
        $isUserRegistered = $event->users()->where('user_id', auth()->id())->exists();

        return Inertia::render('Employee/Event/Show', [
            'event' => [
                'id' => $event->id,
                'eventName' => $event->eventName,
                'eventDescription' => $event->eventDescription,
                'eventImage' => $event->eventImage ? Storage::url($event->eventImage) : '/placeholder.svg',
                'sportType' => $event->sportType,
                'status' => $event->status,
                'venue' => $event->venue,
                'customLocationName' => $event->customLocationName,
                'customLocationLink' => $event->customLocationLink,
                'locationType' => $event->locationType,
                'eventDate' => $event->eventDate,
                'startTime' => $event->startTime,
                'endTime' => $event->endTime,
                'maxParticipants' => $event->maxParticipants,
                'numberOfTeams' => $event->numberOfTeams,
                'registrationDeadline' => $event->registrationDeadline,
                'rulesDescription' => $event->rulesDescription,
                'contactEmail' => $event->contactEmail,
                'contactPhone' => $event->contactPhone,
                'organizer' => $event->organizer,
                'notificationsEnabled' => $event->notifyCreation,
                'registrationType' => $event->registrationType,
                'isUserRegistered' => $isUserRegistered,
            ],
        ]);
    }
}
