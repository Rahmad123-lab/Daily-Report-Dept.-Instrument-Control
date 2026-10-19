<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DailyReport;
use App\Models\DailyReportItem;

class DashboardController extends Controller
{
    /**
     * Dashboard DRICS
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        $totalEmployees = User::count();


        /*
        |--------------------------------------------------------------------------
        | Daily Report
        |--------------------------------------------------------------------------
        */

        $totalReports = DailyReport::count();

        $draftReports = DailyReport::where(
            'status',
            'Draft'
        )->count();

        $publishedReports = DailyReport::where(
            'status',
            'Published'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Active Maintenance
        |--------------------------------------------------------------------------
        |
        | Activity dengan status Open atau Progress
        |
        */

        $activeMaintenance = DailyReportItem::whereIn(
            'status',
            [
                'Open',
                'Progress',
            ]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Completed Maintenance
        |--------------------------------------------------------------------------
        */

        $completedMaintenance = DailyReportItem::where(
            'status',
            'Done'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Daily Reports
        |--------------------------------------------------------------------------
        */

        $recentReports = DailyReport::with([
            'creator',
            'items',
        ])
            ->latest('report_date')
            ->latest('id')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Maintenance Activities
        |--------------------------------------------------------------------------
        */

        $maintenanceItems = DailyReportItem::with([
            'dailyReport',
            'workers',
        ])
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard',
            compact(
                'totalEmployees',
                'totalReports',
                'draftReports',
                'publishedReports',
                'activeMaintenance',
                'completedMaintenance',
                'recentReports',
                'maintenanceItems'
            )
        );
    }
}