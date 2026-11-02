<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCompetency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrainingRecommendationController extends Controller
{
    /**
     * Display training recommendations based on competency gaps.
     *
     * Admin:
     *   Can see recommendations for all active employees.
     *
     * Other active users:
     *   Can only see their own recommendations.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        $baseQuery = EmployeeCompetency::query()
            ->whereNotNull('current_level')
            ->whereColumn('current_level', '<', 'target_level')
            ->whereHas('employee', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('competency', function ($query) {
                $query->where('is_active', true);
            });

        // Restrict non-admin users to their own competency records.
        if (!$isAdmin) {
            $baseQuery->where('user_id', $user->id);
        }

        // Total competency gaps.
        $totalGapRecords = (clone $baseQuery)->count();

        // Total accumulated gap levels.
        $totalGapLevels = (int) (clone $baseQuery)->sum(
            DB::raw('target_level - current_level')
        );

        // Load mapped training programs that are still relevant.
        $recommendations = (clone $baseQuery)
            ->with([
                'employee:id,name,nik',

                'competency' => function ($query) {
                    $query->select(
                        'id',
                        'code',
                        'name',
                        'category',
                        'is_active'
                    );
                },

                'competency.trainings' => function ($query) {
                    $query->where(function ($builder) {
                        $builder
                            ->where(function ($subquery) {
                                $subquery
                                    ->where('status', 'planned')
                                    ->whereDate(
                                        'start_date',
                                        '>=',
                                        today()
                                    );
                            })
                            ->orWhere(function ($subquery) {
                                $subquery
                                    ->where('status', 'in_progress')
                                    ->whereDate(
                                        'end_date',
                                        '>=',
                                        today()
                                    );
                            });
                    })
                    ->orderBy('start_date')
                    ->orderBy('title');
                },
            ])
            ->orderByRaw('(target_level - current_level) DESC')
            ->orderBy('assessed_at')
            ->paginate(12)
            ->withQueryString();

        return view('training-recommendations.index', compact(
            'recommendations',
            'totalGapRecords',
            'totalGapLevels',
            'isAdmin'
        ));
    }
}
