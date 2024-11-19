<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Access the 'group' route parameter
        $group = $request->route('group');
        // Check if the user is part of the group or leader of the group
        if ($request->user() && ($request->user()->groups->contains($group) || $group->user_id === $request->user()->id)) {
            return $next($request);
        }

        // Redirect to the groups page if user doesn't have access
        return redirect()->route('employee.groups');

    }
}
