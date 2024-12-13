<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use App\Models\SessionFeedback;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function session(Request $request, Session $session)
    {
        $request->validate([
            'comment' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        DB::table('session_feedback')->insert([
            'session_id' => $session->id,
            'user_id' => auth()->id(),
            'comments' => $request->comment,
            'rating' => $request->rating,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Feedback submitted successfully.');
    }
}
