<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public static function getPopularSportsData($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->toDateString();
        $endDate = Carbon::parse($endDate)->toDateString();

        // Query to get the sports with the most events and sessions
        $sportsData = DB::table('sports')
            ->leftJoin('events', 'sports.id', '=', 'events.sport_id')
            ->leftJoin('groups', 'sports.id', '=', 'groups.sport_id')
            ->leftJoin('group_sessions', 'groups.id', '=', 'group_sessions.group_id')
            // ->whereBetween('events.event_date', [$startDate, $endDate])
            // ->where('events.status', 'upcoming')
            // ->whereBetween('group_sessions.date_time', [$startDate, $endDate])
            ->select(
                'sports.id as sport_id',
                'sports.name as sport_name',
                DB::raw('COUNT(DISTINCT events.id) + COUNT(DISTINCT group_sessions.id) as total_events_sessions')
            )
            ->groupBy('sports.id', 'sports.name')
            ->orderByDesc(DB::raw('total_events_sessions'))
            ->get();

        return $sportsData;
    }

    // public static function getRegistrationTrendsData($startDate, $endDate)
    // {
    //     $startDate = Carbon::parse($startDate)->toDateString();
    //     $endDate = Carbon::parse($endDate)->toDateString();

    //     // Query to get the registration trends

    //     return $registrationTrendsData;
    // }

    public static function activeGroups($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->toDateString();
        $endDate = Carbon::parse($endDate)->toDateString();

        $activeGroups = Group::select('groups.id', 'groups.name')
            ->withCount([
                // Count messages for each group within the given date range
                'messages' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                },
                // Count sessions for each group within the given date range
                'sessions' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date_time', [$startDate, $endDate]);
                }
            ])
            // Calculate the total activity (sum of messages and sessions)
            ->addSelect(DB::raw('
            (SELECT COUNT(*) FROM messages WHERE messages.group_id = groups.id AND messages.created_at BETWEEN "' . $startDate . '" AND "' . $endDate . '")
            +
            (SELECT COUNT(*) FROM group_sessions WHERE group_sessions.group_id = groups.id AND group_sessions.date_time BETWEEN "' . $startDate . '" AND "' . $endDate . '")
            AS total_activity
        '))
            // Order by total activity in descending order
            ->orderByDesc('total_activity')
            ->get();

        return $activeGroups;
    }
}
