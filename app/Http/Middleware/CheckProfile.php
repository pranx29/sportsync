<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user has a profile
        if (!$user->profile) {
            return redirect()->route('create.about');
        }

        // Check if user associated with a sport
        if ($user->sports->isEmpty()) {
            return redirect()->route('create.interest');
        }

        // Check if user associated with a availability
        if ($user->availabilities->isEmpty()) {
            return redirect()->route('create.availability');
        }

        // If all checks pass, continue with the request
        return $next($request);
    }
}
