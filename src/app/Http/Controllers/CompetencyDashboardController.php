<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCompetency;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompetencyDashboardController extends Controller
{
    /**
     * Display competency gap dashboard.
     *
     * Admin-only route.
     */
    public function index(): View
    {
        // Only consider records belonging to active employees.
        $baseQuery = EmployeeCompetency::query()
            ->whereHas('employee', function ($query) {
                $query->where('status', 'active');
            });

        $assessedQuery = (clone $baseQuery)
            ->whereNotNull('current_level');

        $gapQuery = (clone $assessedQuery)
            ->whereColumn('current_level', '<', 'target_level');

        // Main metrics.
        $totalAssignments = (clone $baseQuery)->count();

        $assessedCount = (clone $assessedQuery)->count();

        $notAssessedCount = (clone $baseQuery)
            ->whereNull('current_level')
            ->count();

        $targetMetCount = (clone $assessedQuery)
            ->whereColumn('current_level', '>=', 'target_level')
            ->count();

        $gapCount = (clone $gapQuery)->count();

        $totalGapLevels = (int) (clone $gapQuery)->sum(
            DB::raw('target_level - current_level')
        );

        $assessmentRate = $totalAssignments > 0
            ? round(($assessedCount / $totalAssignments) * 100, 1)
            : 0;

        $targetMetRate = $assessedCount > 0
            ? round(($targetMetCount / $assessedCount) * 100, 1)
            : 0;

        // Gap analysis grouped by competency category.
        $gapByCategory = (clone $gapQuery)
            ->join(
                'competencies',
                'employee_competencies.competency_id',
                '=',
                'competencies.id'
            )
            ->select('competencies.category')
            ->selectRaw('COUNT(*) AS affected_records')
            ->selectRaw(
                'SUM(employee_competencies.target_level - employee_competencies.current_level) AS total_gap'
            )
            ->groupBy('competencies.category')
            ->orderByDesc('total_gap')
            ->get();

        // Highest competency gaps.
        $priorityRecords = (clone $gapQuery)
            ->with([
                'employee:id,name,nik',
                'competency:id,code,name,category',
                'assessor:id,name',
            ])
            ->orderByRaw('(target_level - current_level) DESC')
            ->orderBy('assessed_at')
            ->paginate(10);

        return view('competencies.dashboard', compact(
            'totalAssignments',
            'assessedCount',
            'notAssessedCount',
            'targetMetCount',
            'gapCount',
            'totalGapLevels',
            'assessmentRate',
            'targetMetRate',
            'gapByCategory',
            'priorityRecords',
        ));
    }
}