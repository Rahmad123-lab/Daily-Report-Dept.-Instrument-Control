<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DailyReport;
use App\Models\DailyReportItem;

class DashboardController extends Controller
{
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
        | Maintenance
        |--------------------------------------------------------------------------
        */

        $activeMaintenance = DailyReportItem::whereIn('status', [
            'Open',
            'Progress',
        ])->count();


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
        | Maintenance Monitoring
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

        return view('dashboard', compact(
            'totalEmployees',
            'activeMaintenance',
            'totalReports',
            'draftReports',
            'publishedReports',
            'maintenanceItems'
        ));
    }
}