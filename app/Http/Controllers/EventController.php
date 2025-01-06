<?php

namespace App\Http\Controllers;

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

        return Inertia::render('Employee/Event/Index', [
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function show()
    {
        return Inertia::render('Employee/Event/Show');
    }
}
