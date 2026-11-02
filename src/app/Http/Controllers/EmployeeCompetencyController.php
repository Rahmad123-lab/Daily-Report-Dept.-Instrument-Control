<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\EmployeeCompetency;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeCompetencyController extends Controller
{
    /**
     * Display competency records.
     * Non-admin users can only see their own records.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'competency_id' => [
                'nullable',
                'integer',
                'exists:competencies,id',
            ],
        ]);

        $query = EmployeeCompetency::query()
            ->with([
                'employee:id,name,nik',
                'competency:id,code,name,category',
                'assessor:id,name',
            ]);

        if ($request->user()->role !== 'admin') {
            $query->where('user_id', $request->user()->id);
        } elseif (!empty($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }

        $query
            ->when(
                filled($validated['competency_id'] ?? null),
                fn ($builder) => $builder->where(
                    'competency_id',
                    $validated['competency_id']
                )
            )
            ->when(
                filled($validated['search'] ?? null),
                function ($builder) use ($validated) {
                    $search = $validated['search'];

                    $builder->where(function ($query) use ($search) {
                        $query
                            ->whereHas('competency', function ($subquery) use ($search) {
                                $subquery
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('code', 'like', "%{$search}%");
                            })
                            ->orWhereHas('employee', function ($subquery) use ($search) {
                                $subquery
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('nik', 'like', "%{$search}%");
                            });
                    });
                }
            );

        $employeeCompetencies = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $competencies = collect();
$employees = collect();

if ($request->user()->role === 'admin') {
    $competencies = Competency::query()
        ->where('is_active', true)
        ->orderBy('category')
        ->orderBy('name')
        ->get(['id', 'code', 'name', 'category']);

    $employees = User::query()
        ->where('status', 'active')
        ->orderBy('name')
        ->get(['id', 'name', 'nik']);
}

return view(
    'employee-competencies.index',
    compact(
        'employeeCompetencies',
        'competencies',
        'employees'
    )
);
    }

    /**
     * Assign a competency to an employee.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where(fn ($query) => $query->where('status', 'active')),
            ],

            'competency_id' => [
                'required',
                Rule::exists('competencies', 'id')
                    ->where(fn ($query) => $query->where('is_active', true)),
            ],

            'current_level' => [
                'nullable',
                'integer',
                'between:1,5',
            ],

            'target_level' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'assessed_at' => [
                'nullable',
                'date',
            ],

            'evidence' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        $exists = EmployeeCompetency::query()
            ->where('user_id', $validated['user_id'])
            ->where('competency_id', $validated['competency_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'competency_id' => 'Kompetensi ini sudah ditetapkan kepada karyawan tersebut.',
                ]);
        }

        $currentLevel = $validated['current_level'] ?? null;

        EmployeeCompetency::create([
            ...$validated,

            'assessed_by' => $currentLevel !== null
                ? $request->user()->id
                : null,

            'assessed_at' => $currentLevel !== null
                ? ($validated['assessed_at'] ?? now()->toDateString())
                : null,
        ]);

        return redirect()
            ->route('employee-competencies.index')
            ->with('success', 'Kompetensi karyawan berhasil ditambahkan.');
    }

    /**
     * Update employee competency assessment.
     */
    public function update(
        Request $request,
        EmployeeCompetency $employeeCompetency
    ): RedirectResponse {
        $validated = $request->validate([
            'current_level' => [
                'nullable',
                'integer',
                'between:1,5',
            ],

            'target_level' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'assessed_at' => [
                'nullable',
                'date',
            ],

            'evidence' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        $currentLevel = $validated['current_level'] ?? null;

        $employeeCompetency->update([
            ...$validated,

            'assessed_by' => $currentLevel !== null
                ? $request->user()->id
                : null,

            'assessed_at' => $currentLevel !== null
                ? ($validated['assessed_at'] ?? now()->toDateString())
                : null,
        ]);

        return redirect()
            ->route('employee-competencies.index')
            ->with('success', 'Penilaian kompetensi berhasil diperbarui.');
    }

    /**
     * Delete employee competency record.
     */
    public function destroy(
        EmployeeCompetency $employeeCompetency
    ): RedirectResponse {
        $employeeCompetency->delete();

        return redirect()
            ->route('employee-competencies.index')
            ->with('success', 'Data kompetensi karyawan berhasil dihapus.');
    }
}