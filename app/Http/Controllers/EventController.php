<?php

namespace App\Http\Controllers;

use App\Models\EventTeam;
use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('date', '>=', now())->get()->load('sport', 'venue', 'teams');
        $upcomingEvents = $upcomingEvents->filter(function ($event) {
            return $event->status === 'upcoming' && !$event->isUserRegistered(auth()->user());
        });

        $registeredEvents = auth()->user()->events()->get()->load('sport', 'venue');

        return Inertia::render('Employee/Event/Index', [
            'upcomingEvents' => $upcomingEvents,
            'registeredEvents' => $registeredEvents,
        ]);
    }

    public function show(Event $event)
    {

        // if the event is team based, find current user's team
        if ($event->registration_type === 'team') {
            $team = auth()->user()->teams()->where('event_teams.event_id', $event->id)->first()?->load('users');
        }

        return Inertia::render('Employee/Event/Show', [
            'event' => $event->load('sport', 'venue', 'users', 'feedbacks'),
            'myTeam' => $team,
        ]);
    }

    public function register(Request $request, Event $event)
    {
        if ($event->users()->where('user_id', auth()->user()->id)->exists()) {
            return redirect()->route('employee.events.index');
        }

        if ($event->users()->count() >= $event->max_participants) {
            return redirect()->route('employee.events.index');
        }

        if ($event->registration_type === 'team') {
            $team = EventTeam::find($request->input('selectedTeam'));

            if (!$team) {
                return redirect()->route('employee.events.index')->withErrors([
                    'selectedTeam' => 'Select an existing team to join the event.'
                ]);
            }
            $team->users()->attach(auth()->user(), ['event_id' => $event->id]);

            return redirect()->route('employee.events.index');
        }

        $event->users()->attach(auth()->user());

        return redirect()->route('employee.events.index');
    }

    public function unregister(Event $event)
    {

        $event->users()->detach(auth()->user());

        return redirect()->route('employee.events.index');
    }

    public function feedback(Request $request, Event $event)
    {
        // Check if user is a participant of the event
        if (!$event->users->contains(auth()->id())) {
            return back()->with('error', 'You must join the event to submit feedback.');
        }

        // Check if the event has already ended
        if ($event->date > now()) {
            return back()->with('error', 'You cannot submit feedback for an upcoming event.');
        }

        // Check if user has already submitted feedback for this event
        if(auth()->user()->eventFeedbacks()->where('event_id', $event->id)->exists()) {
            return back()->with('error', 'You have already submitted feedback for this event.');
        }

        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        auth()->user()->eventFeedbacks()->create([
            'event_id' => $event->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Feedback submitted successfully.');
    }
}
