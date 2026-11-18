<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCompetencyAssessment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeCompetencyAssessmentHistoryController extends Controller
{
    /**
     * Display competency assessment history.
     *
     * Admin can view all employees.
     * Non-admin users can only view their own history.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:150',
            ],

            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'from_date' => [
                'nullable',
                'date',
            ],

            'to_date' => [
                'nullable',
                'date',
                'after_or_equal:from_date',
            ],
        ]);

        $isAdmin = $request->user()->role === 'admin';

        $query = EmployeeCompetencyAssessment::query()
            ->with([
                'employee:id,name,nik',
                'competency:id,code,name,category',
                'assessor:id,name',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Access Control
        |--------------------------------------------------------------------------
        */

        if (!$isAdmin) {
            $query->where(
                'user_id',
                $request->user()->id
            );
        } elseif (!empty($validated['user_id'])) {
            $query->where(
                'user_id',
                $validated['user_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */

        if (filled($validated['search'] ?? null)) {
            $search = $validated['search'];

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('assessor_name', 'like', "%{$search}%")
                    ->orWhere('competency_code', 'like', "%{$search}%")
                    ->orWhere('competency_name', 'like', "%{$search}%")
                    ->orWhere('competency_category', 'like', "%{$search}%")
                    ->orWhere('evidence', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->where(
                            'nik',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if (filled($validated['category'] ?? null)) {
            $query->where(
                'competency_category',
                $validated['category']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Assessment Date Filter
        |--------------------------------------------------------------------------
        */

        if (filled($validated['from_date'] ?? null)) {
            $query->whereDate(
                'assessed_at',
                '>=',
                $validated['from_date']
            );
        }

        if (filled($validated['to_date'] ?? null)) {
            $query->whereDate(
                'assessed_at',
                '<=',
                $validated['to_date']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Retrieve History
        |--------------------------------------------------------------------------
        */

        $assessmentHistories = $query
            ->orderByDesc('assessed_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $employees = collect();

        if ($isAdmin) {
            $employees = User::query()
                ->whereIn(
                    'id',
                    EmployeeCompetencyAssessment::query()
                        ->select('user_id')
                        ->whereNotNull('user_id')
                        ->distinct()
                )
                ->orderBy('name')
                ->get(['id', 'name', 'nik']);
        }

        $categories = EmployeeCompetencyAssessment::query()
            ->whereNotNull('competency_category')
            ->where('competency_category', '<>', '')
            ->distinct()
            ->orderBy('competency_category')
            ->pluck('competency_category');

        return view(
            'employee-competencies.history',
            compact(
                'assessmentHistories',
                'employees',
                'categories',
                'isAdmin'
            )
        );
    }
}
