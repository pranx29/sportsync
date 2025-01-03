<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        return Inertia::render('Employee/Event/Index');
    }

    public function show()
    {
        return Inertia::render('Employee/Event/Show');
    }
}
