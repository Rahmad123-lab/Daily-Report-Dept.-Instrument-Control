<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrainingEvaluationReportController extends Controller
{
    /**
     * Menampilkan laporan evaluasi kompetensi training.
     */
    public function index(Request $request, Training $training): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($validated['search'] ?? '');
        $category = trim($validated['category'] ?? '');

        /*
        |--------------------------------------------------------------------------
        | Query dasar berdasarkan training
        |--------------------------------------------------------------------------
        */

        $baseQuery = DB::table('training_competency_evaluations as evaluations')
            ->join(
                'training_participants as participants',
                'participants.id',
                '=',
                'evaluations.training_participant_id'
            )
            ->where('participants.training_id', $training->id);

        /*
        |--------------------------------------------------------------------------
        | Daftar kategori yang tersedia
        |--------------------------------------------------------------------------
        */

        $categories = (clone $baseQuery)
            ->whereNotNull('evaluations.competency_category')
            ->where('evaluations.competency_category', '<>', '')
            ->distinct()
            ->orderBy('evaluations.competency_category')
            ->pluck('evaluations.competency_category');

        /*
        |--------------------------------------------------------------------------
        | Filter pencarian dan kategori
        |--------------------------------------------------------------------------
        */

        $filteredQuery = clone $baseQuery;

        if ($search !== '') {
            $filteredQuery->where(function ($query) use ($search) {
                $query
                    ->where(
                        'evaluations.employee_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'evaluations.competency_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'evaluations.competency_code',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        if ($category !== '') {
            $filteredQuery->where(
                'evaluations.competency_category',
                $category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Summary berdasarkan filter aktif
        |--------------------------------------------------------------------------
        */

        $totalEvaluations = (clone $filteredQuery)
            ->count('evaluations.id');

        $totalEmployees = (clone $filteredQuery)
            ->distinct()
            ->count('evaluations.user_id');

        $averagePostLevel = (clone $filteredQuery)
            ->avg('evaluations.post_training_level');

        $targetAchieved = (clone $filteredQuery)
            ->whereColumn(
                'evaluations.post_training_level',
                '>=',
                'evaluations.target_level'
            )
            ->count('evaluations.id');

        $targetNotAchieved = $totalEvaluations - $targetAchieved;

        $targetAchievementRate = $totalEvaluations > 0
            ? round(
                ($targetAchieved / $totalEvaluations) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Detail evaluasi
        |--------------------------------------------------------------------------
        */

        $evaluations = (clone $filteredQuery)
            ->select([
                'evaluations.id',
                'evaluations.training_participant_id',
                'evaluations.user_id',
                'evaluations.employee_name',
                'evaluations.competency_code',
                'evaluations.competency_name',
                'evaluations.competency_category',
                'evaluations.pre_training_level',
                'evaluations.post_training_level',
                'evaluations.target_level',
                'evaluations.assessed_at',
                'evaluations.assessed_by',
                'evaluations.evidence',
                'evaluations.notes',
            ])
            ->orderBy('evaluations.employee_name')
            ->orderBy('evaluations.competency_name')
            ->paginate(15)
            ->withQueryString();

        return view('training-evaluations.index', [
            'training' => $training,
            'evaluations' => $evaluations,

            'search' => $search,
            'category' => $category,
            'categories' => $categories,

            'totalEvaluations' => $totalEvaluations,
            'totalEmployees' => $totalEmployees,
            'averagePostLevel' => $averagePostLevel,
            'targetAchieved' => $targetAchieved,
            'targetNotAchieved' => $targetNotAchieved,
            'targetAchievementRate' => $targetAchievementRate,
        ]);
    }
}