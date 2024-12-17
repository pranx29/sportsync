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
        // Check if user is a participant of the session
        if (!$session->participants->contains(auth()->id())) {
            return back()->with('error', 'You must join the session to submit feedback.');
        }
        // Chekc if the session has already ended
        if ($session->date_time > now()) {
            return back()->with('error', 'You cannot submit feedback for a upcoming session.');
        }
        // Check if user has already submitted feedback for this session
        if (SessionFeedback::where('session_id', $session->id)->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already submitted feedback for this session.');
        }

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
