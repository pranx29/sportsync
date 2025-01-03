<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminEventController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Event/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Event/Create');
    }


    // TODO - Implement store method
    public function store(Request $request)
    {

    }

    public function show($id, Request $request)
    {
        // TODO - Fetch event by id
        $editMode = $request->query('editMode', false);

        return Inertia::render('Admin/Event/Show', [
            'editMode' => $editMode,
        ]);
    }
}
