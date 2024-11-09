<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (\Exception $e) {
            dd('Authentication failed: ' . $e->getMessage());
        }
        $request->session()->regenerate();

        // If user has already created a profile then redirect to dashboard
        $profile = Profile::where('user_id', Auth::id())->first();
        if ($profile) {
            return redirect()->intended('/dashboard')->with('status', 'Authenticated');
        }

        // If user has not created a profile, redirect to create profile page
        sleep(1);
        return redirect()->route('create.about', [
        ])->with('first', 'Authenticated');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
