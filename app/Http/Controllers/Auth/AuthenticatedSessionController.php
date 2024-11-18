<?php

namespace App\Http\Controllers\Auth;

use Redirect;
use App\Models\User;
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
        } catch (\Illuminate\Auth\AuthenticationException | \Illuminate\Validation\ValidationException $e) {
            \Log::warning('Login failed for email: ' . $request->email);
            return Redirect::back()->withErrors(['error' => 'Incorrect email or password']);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Redirect based on user role
        switch ($user->role) {
            case User::EMPLOYEE:
                // Check if the user has a profile and if their profile is created
                $profile = Profile::where('user_id', $user->id)->first();
                if ($profile && $user->is_profile_created) {
                    return Redirect::route('dashboard')->with('status', 'Authenticated');
                } else {
                    return Redirect::route('create.about')->with('status', 'Please complete your profile');
                }

            case User::ADMIN:
                return Redirect::route('admin.dashboard')->with('status', 'Welcome, Admin');

            default:
                // Log the unexpected role
                \Log::error('User with id ' . $user->id . ' has an unrecognized role: ' . $user->role);

                // Redirect to a fallback route, e.g., home or an error page
                return Redirect::route('home')->withErrors(['error' => 'Unexpected user role']);
        }
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
