<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompetencyController extends Controller
{
    /**
     * Display competency catalog.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $competencies = Competency::query()
            ->withCount('employeeCompetencies')
            ->when(
                $request->user()->role !== 'admin',
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                filled($validated['search'] ?? null),
                function ($query) use ($validated) {
                    $search = $validated['search'];

                    $query->where(function ($builder) use ($search) {
                        $builder
                            ->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%")
                            ->orWhere('category', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                filled($validated['category'] ?? null),
                fn ($query) => $query->where(
                    'category',
                    $validated['category']
                )
            )
            ->when(
                $request->user()->role === 'admin'
                    && filled($validated['status'] ?? null),
                fn ($query) => $query->where(
                    'is_active',
                    $validated['status'] === 'active'
                )
            )
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('competencies.index', compact('competencies'));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('competencies.create');
    }

    /**
     * Store competency.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Competency::create($validated);

        return redirect()
            ->route('competencies.index')
            ->with('success', 'Kompetensi berhasil ditambahkan.');
    }

    /**
     * Display competency details.
     */
    public function show(
        Request $request,
        Competency $competency
    ): View {
        if (
            $request->user()->role !== 'admin'
            && !$competency->is_active
        ) {
            abort(404);
        }

        $competency->loadCount('employeeCompetencies');

        if ($request->user()->role === 'admin') {
            $competency->load([
                'employeeCompetencies.employee:id,name,nik',
            ]);
        }

        return view('competencies.show', compact('competency'));
    }

    /**
     * Show edit form.
     */
    public function edit(Competency $competency): View
    {
        return view('competencies.edit', compact('competency'));
    }

    /**
     * Update competency.
     */
    public function update(
        Request $request,
        Competency $competency
    ): RedirectResponse {
        $validated = $request->validate(
            $this->rules($competency)
        );

        $competency->update($validated);

        return redirect()
            ->route('competencies.show', $competency)
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    /**
     * Delete competency if it is not assigned.
     */
    public function destroy(
        Competency $competency
    ): RedirectResponse {
        if ($competency->employeeCompetencies()->exists()) {
            return back()->with(
                'error',
                'Kompetensi masih digunakan oleh karyawan. Nonaktifkan kompetensi tersebut sebagai gantinya.'
            );
        }

        $competency->delete();

        return redirect()
            ->route('competencies.index')
            ->with('success', 'Kompetensi berhasil dihapus.');
    }

    /**
     * Shared validation rules.
     */
    private function rules(?Competency $competency = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('competencies', 'code')
                    ->ignore($competency?->id),
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}