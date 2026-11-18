<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingEvaluationReportExportController extends Controller
{
    /**
     * Export laporan evaluasi training ke PDF.
     *
     * Mendukung filter pencarian dan kategori yang sama
     * dengan halaman Training Evaluation Report.
     */
    public function export(Request $request, Training $training)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($validated['search'] ?? '');
        $category = trim($validated['category'] ?? '');

        /*
        |--------------------------------------------------------------------------
        | Query dasar evaluasi
        |--------------------------------------------------------------------------
        */

        $query = DB::table('training_competency_evaluations as evaluations')
            ->join(
                'training_participants as participants',
                'participants.id',
                '=',
                'evaluations.training_participant_id'
            )
            ->where('participants.training_id', $training->id);

        /*
        |--------------------------------------------------------------------------
        | Search filter
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery
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

        /*
        |--------------------------------------------------------------------------
        | Category filter
        |--------------------------------------------------------------------------
        */

        if ($category !== '') {
            $query->where(
                'evaluations.competency_category',
                $category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalEvaluations = (clone $query)
            ->count('evaluations.id');

        $totalEmployees = (clone $query)
            ->distinct()
            ->count('evaluations.user_id');

        $averagePostLevel = (clone $query)
            ->avg('evaluations.post_training_level');

        $targetAchieved = (clone $query)
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
        | Evaluation Details
        |--------------------------------------------------------------------------
        */

        $evaluations = (clone $query)
            ->select([
                'evaluations.id',
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
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView('training-evaluations.pdf', [
            'training' => $training,
            'evaluations' => $evaluations,

            'search' => $search,
            'category' => $category,

            'totalEvaluations' => $totalEvaluations,
            'totalEmployees' => $totalEmployees,
            'averagePostLevel' => $averagePostLevel,
            'targetAchieved' => $targetAchieved,
            'targetNotAchieved' => $targetNotAchieved,
            'targetAchievementRate' => $targetAchievementRate,
        ])->setPaper('a4', 'landscape');

        $filename = 'training-evaluation-'
            . Str::slug($training->title)
            . '.pdf';

        return $pdf->download($filename);
    }
}