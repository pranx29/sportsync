<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Profile;
use App\Models\Session;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {

        $summaryData = [
            'totalEmployees' => User::where('role', 'employee')->count(),
            'employeesJoinedThisMonth' => User::where('role', 'employee')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'totalEvents' => Event::count(),
            'eventsThisMonth' => Event::whereMonth('event_date', now()->month)
                ->whereYear('event_date', now()->year)
                ->count(),
            'totalProfiles' => Profile::count(),
            'profilesLastSevenDays' => Profile::where('created_at', '>=', now()->subDays(7))->count(),
            'totalGroupSessions' => Session::count(),
            'groupSessionsThisWeek' => Session::where('created_at', '>=', now()->startOfWeek())
                ->where('session_date', '<=', now()->endOfWeek())
                ->count(),
        ];

        $activeGroups = Group::where('is_active', true)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->withCount(['messages', 'users', 'sessions'])
            ->get()
            ->sortByDesc(function ($group) {
                return $group->messages_count + $group->users_count + $group->sessions_count;
            })
            ->take(5);

        $employeeSummary = User::select('profiles.department_id', 'departments.name as department_name', \DB::raw('count(*) as total_employees'))
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('departments', 'profiles.department_id', '=', 'departments.id')
            ->withCount([
            'events as active_in_events' => function ($query) {
                $query->whereMonth('event_date', now()->month)
                ->whereYear('event_date', now()->year);
            },
            'groups as member_in_groups'
            ])
            ->groupBy('profiles.department_id', 'departments.name')
            ->get();


        return Inertia::render(
            'Admin/Dashboard/Index',
            [
                'summaryData' => $summaryData,
                'activeGroups' => $activeGroups,
                'employeeSummary' => $employeeSummary
            ]
        );
    }
}
