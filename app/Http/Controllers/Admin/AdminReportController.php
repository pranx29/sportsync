<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminReportController extends Controller
{
    public function index()
    {
        $reportType = request('reportType');
        $startDate = request('startDate');
        $endDate = request('endDate');

        $reportData = [];

        // Get the report data based on the report type
        if ($reportType == 'popular-sports') {
            $reportData = Report::getPopularSportsData($startDate, $endDate);
            $reportData = [
                'label' => 'Total Events and Sessions',
                'labels' => $reportData->pluck('sport_name')->toArray(),
                'datasets' => $reportData->pluck('total_events_sessions')->toArray(),

            ];
        } elseif($reportType == 'active-groups') {
            $reportData = Report::activeGroups($startDate, $endDate);
            $reportData = [
                'label' => 'Total Messages and Sessions',
                'labels' => $reportData->pluck('name')->toArray(),
                'datasets' => $reportData->pluck('total_activity')->toArray(),
            ];
        }


        return Inertia::render('Admin/Report/Index', [
            'reportData' => $reportData,
        ]);
    }


}
