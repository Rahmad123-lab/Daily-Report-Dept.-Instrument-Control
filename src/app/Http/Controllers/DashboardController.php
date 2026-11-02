<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DailyReport;
use App\Models\DailyReportItem;
use App\Models\Equipment;
use App\Models\MaintenanceHistory;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\EmployeeCompetency;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display integrated DRICS operational dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $today = now()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $totalEmployees = User::query()
            ->where('status', 'active')
            ->count();

        $pendingEmployees = $isAdmin
            ? User::query()->where('status', 'pending')->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | DAILY REPORT
        |--------------------------------------------------------------------------
        */

        $reportStatusCounts = DailyReport::query()
            ->select('status')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $totalReports = DailyReport::count();

        $draftReports = (int) $reportStatusCounts->get('Draft', 0);

        $publishedReports = (int) $reportStatusCounts->get('Published', 0);

        /*
        |--------------------------------------------------------------------------
        | DAILY REPORT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $dailyItemStatusCounts = DailyReportItem::query()
            ->select('status')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $openDailyActivities = (int) $dailyItemStatusCounts->get('Open', 0);

        $progressDailyActivities = (int) $dailyItemStatusCounts->get('Progress', 0);

        $completedDailyActivities = (int) $dailyItemStatusCounts->get('Done', 0);

        $activeMaintenance = $openDailyActivities + $progressDailyActivities;

        /*
        |--------------------------------------------------------------------------
        | EQUIPMENT
        |--------------------------------------------------------------------------
        */

        $equipmentStatusCounts = Equipment::query()
            ->select('status')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $totalEquipments = Equipment::count();

        $activeEquipments = (int) $equipmentStatusCounts->get('Active', 0);

        $standbyEquipments = (int) $equipmentStatusCounts->get('Standby', 0);

        $maintenanceEquipments = (int) $equipmentStatusCounts->get('Maintenance', 0);

        $breakdownEquipments = (int) $equipmentStatusCounts->get('Breakdown', 0);

        $decommissionedEquipments = (int) $equipmentStatusCounts->get(
            'Decommission',
            0
        );

        /*
        |--------------------------------------------------------------------------
        | MAINTENANCE HISTORY
        |--------------------------------------------------------------------------
        |
        | This is separate from DailyReportItem.
        |
        */

        $maintenanceHistoryStatusCounts = MaintenanceHistory::query()
            ->select('status')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $maintenanceResultCounts = MaintenanceHistory::query()
            ->select('result')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('result')
            ->pluck('aggregate', 'result');

        $totalMaintenanceHistories = MaintenanceHistory::count();

        $maintenanceOpenCount = (int) $maintenanceHistoryStatusCounts
            ->get('Open', 0);

        $maintenanceProgressCount = (int) $maintenanceHistoryStatusCounts
            ->get('Progress', 0);

        $maintenanceCompletedCount = (int) $maintenanceHistoryStatusCounts
            ->get('Completed', 0);

        $maintenancePendingResults = (int) $maintenanceResultCounts
            ->get('Pending', 0);

        $maintenanceFailedResults = (int) $maintenanceResultCounts
            ->get('Failed', 0);

        /*
        |--------------------------------------------------------------------------
        | TRAINING
        |--------------------------------------------------------------------------
        */

        $trainingStatusCounts = Training::query()
            ->select('status')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $totalTrainings = Training::count();

        $plannedTrainings = (int) $trainingStatusCounts->get('planned', 0);

        $inProgressTrainings = (int) $trainingStatusCounts->get(
            'in_progress',
            0
        );

        $completedTrainings = (int) $trainingStatusCounts->get(
            'completed',
            0
        );

        $cancelledTrainings = (int) $trainingStatusCounts->get(
            'cancelled',
            0
        );

        /*
        |--------------------------------------------------------------------------
        | TRAINING PARTICIPANTS
        |--------------------------------------------------------------------------
        */

        $participantRegistrationCounts = TrainingParticipant::query()
            ->select('registration_status')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('registration_status')
            ->pluck('aggregate', 'registration_status');

        $participantAttendanceCounts = TrainingParticipant::query()
            ->select('attendance_status')
            ->selectRaw('COUNT(*) AS aggregate')
            ->groupBy('attendance_status')
            ->pluck('aggregate', 'attendance_status');

        $totalTrainingRegistrations = TrainingParticipant::count();

        $pendingTrainingRegistrations = (int) $participantRegistrationCounts
            ->get('registered', 0);

        $approvedTrainingRegistrations = (int) $participantRegistrationCounts
            ->get('approved', 0);

        $presentTrainingParticipants = (int) $participantAttendanceCounts
            ->get('present', 0);

        $absentTrainingParticipants = (int) $participantAttendanceCounts
            ->get('absent', 0);

        /*
        |--------------------------------------------------------------------------
        | UPCOMING TRAINING
        |--------------------------------------------------------------------------
        */

        $upcomingTrainings = Training::query()
            ->withCount([
                'participants as active_participants_count' => function ($query) {
                    $query->whereIn('registration_status', [
                        'registered',
                        'approved',
                    ]);
                },
            ])
            ->where('status', 'planned')
            ->whereDate('start_date', '>=', $today)
            ->orderBy('start_date')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | COMPETENCY ASSESSMENT
        |--------------------------------------------------------------------------
        |
        | Admin sees all active employees.
        | Other users only see their own competency records.
        |
        */

        $competencyQuery = EmployeeCompetency::query()
            ->whereHas('employee', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('competency', function ($query) {
                $query->where('is_active', true);
            });

        if (!$isAdmin) {
            $competencyQuery->where('user_id', $user->id);
        }

        $assessedCompetencyQuery = (clone $competencyQuery)
            ->whereNotNull('current_level');

        $gapCompetencyQuery = (clone $assessedCompetencyQuery)
            ->whereColumn('current_level', '<', 'target_level');

        $totalCompetencyAssignments = (clone $competencyQuery)->count();

        $assessedCompetencies = (clone $assessedCompetencyQuery)->count();

        $notAssessedCompetencies = (clone $competencyQuery)
            ->whereNull('current_level')
            ->count();

        $targetMetCompetencies = (clone $assessedCompetencyQuery)
            ->whereColumn('current_level', '>=', 'target_level')
            ->count();

        $competencyGapCount = (clone $gapCompetencyQuery)->count();

        $totalCompetencyGapLevels = (int) (clone $gapCompetencyQuery)
            ->sum(DB::raw('target_level - current_level'));

        $competencyAssessmentRate = $totalCompetencyAssignments > 0
            ? round(
                ($assessedCompetencies / $totalCompetencyAssignments) * 100,
                1
            )
            : 0;

        $competencyTargetMetRate = $assessedCompetencies > 0
            ? round(
                ($targetMetCompetencies / $assessedCompetencies) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TRAINING RECOMMENDATION COUNT
        |--------------------------------------------------------------------------
        */

        $eligibleTrainingQuery = function ($query) use ($today) {
            $query->where(function ($builder) use ($today) {
                $builder
                    ->where(function ($planned) use ($today) {
                        $planned
                            ->where('status', 'planned')
                            ->whereDate('start_date', '>=', $today);
                    })
                    ->orWhere(function ($inProgress) use ($today) {
                        $inProgress
                            ->where('status', 'in_progress')
                            ->whereDate('end_date', '>=', $today);
                    });
            });
        };

        $recommendationCount = (clone $gapCompetencyQuery)
            ->whereHas('competency', function ($query) use (
                $eligibleTrainingQuery
            ) {
                $query->whereHas(
                    'trainings',
                    $eligibleTrainingQuery
                );
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | COMPETENCY PRIORITY
        |--------------------------------------------------------------------------
        */

        $priorityCompetencies = (clone $gapCompetencyQuery)
            ->with([
                'employee:id,name,nik',
                'competency:id,code,name,category',
            ])
            ->orderByRaw(
                '(target_level - current_level) DESC'
            )
            ->orderBy('assessed_at')
            ->take(5)
            ->get();

        
/*
|--------------------------------------------------------------------------
| DAILY REPORT TREND - LAST SIX MONTHS
|--------------------------------------------------------------------------
|
| Database-independent aggregation.
| Supports MySQL in production and SQLite in automated tests.
|
*/

$trendStart = now()->startOfMonth()->subMonths(5);

// Retrieve only report dates for the last six months.
$reportDateRows = DailyReport::query()
    ->whereBetween('report_date', [
        $trendStart->toDateString(),
        $today,
    ])
    ->get(['report_date']);

// Group monthly counts using Carbon instead of SQL YEAR/MONTH.
$trendCounts = $reportDateRows->countBy(
    fn (DailyReport $report) => $report->report_date->format('Y-m')
);

// Generate six months, including months with zero reports.
$reportTrend = collect(range(0, 5))
    ->map(function ($offset) use ($trendStart, $trendCounts) {
        $month = $trendStart->copy()->addMonths($offset);

        $key = $month->format('Y-m');

        return [
            'label' => $month->format('M Y'),
            'total' => (int) $trendCounts->get($key, 0),
        ];
    });

$maxMonthlyReports = max(
    1,
    (int) $reportTrend->max('total')
);


        /*
        |--------------------------------------------------------------------------
        | RECENT DAILY REPORTS
        |--------------------------------------------------------------------------
        */

        $recentReports = DailyReport::query()
            ->with(['creator:id,name'])
            ->withCount('items')
            ->orderByDesc('report_date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT DAILY REPORT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $recentReportActivities = DailyReportItem::query()
            ->with([
                'dailyReport:id,report_number,report_date',
                'workers:id,name',
            ])
            ->latest('updated_at')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT MAINTENANCE HISTORIES
        |--------------------------------------------------------------------------
        */

        $recentMaintenanceHistories = MaintenanceHistory::query()
            ->with([
                'equipment:id,equipment_name,equipment_code,tag_number',
                'creator:id,name',
            ])
            ->orderByDesc('maintenance_date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD RESPONSE
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'isAdmin',

            'totalEmployees',
            'pendingEmployees',

            'totalReports',
            'draftReports',
            'publishedReports',

            'openDailyActivities',
            'progressDailyActivities',
            'completedDailyActivities',
            'activeMaintenance',

            'equipmentStatusCounts',
            'totalEquipments',
            'activeEquipments',
            'standbyEquipments',
            'maintenanceEquipments',
            'breakdownEquipments',
            'decommissionedEquipments',

            'maintenanceHistoryStatusCounts',
            'maintenanceResultCounts',
            'totalMaintenanceHistories',
            'maintenanceOpenCount',
            'maintenanceProgressCount',
            'maintenanceCompletedCount',
            'maintenancePendingResults',
            'maintenanceFailedResults',

            'trainingStatusCounts',
            'totalTrainings',
            'plannedTrainings',
            'inProgressTrainings',
            'completedTrainings',
            'cancelledTrainings',

            'totalTrainingRegistrations',
            'pendingTrainingRegistrations',
            'approvedTrainingRegistrations',
            'presentTrainingParticipants',
            'absentTrainingParticipants',

            'upcomingTrainings',

            'totalCompetencyAssignments',
            'assessedCompetencies',
            'notAssessedCompetencies',
            'targetMetCompetencies',
            'competencyGapCount',
            'totalCompetencyGapLevels',
            'competencyAssessmentRate',
            'competencyTargetMetRate',
            'recommendationCount',
            'priorityCompetencies',

            'reportTrend',
            'maxMonthlyReports',

            'recentReports',
            'recentReportActivities',
            'recentMaintenanceHistories'
        ));
    }
}
