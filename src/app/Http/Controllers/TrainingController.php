<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TrainingController extends Controller
{
    /**
     * Display training list.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'status' => [
                'nullable',
                Rule::in([
                    'planned',
                    'in_progress',
                    'completed',
                    'cancelled',
                ]),
            ],
        ]);

        $trainings = Training::query()
            ->withCount('participants')
            ->when(
                filled($validated['search'] ?? null),
                function ($query) use ($validated) {
                    $search = $validated['search'];

                    $query->where(function ($builder) use ($search) {
                        $builder
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('category', 'like', "%{$search}%")
                            ->orWhere('organizer', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                filled($validated['status'] ?? null),
                fn ($query) => $query->where(
                    'status',
                    $validated['status']
                )
            )
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('trainings.index', compact('trainings'));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        $competencies = Competency::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'category']);

        return view('trainings.create', compact('competencies'));
    }

    /**
     * Store a new training and its competency mappings.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $competencyIds = array_map(
            'intval',
            $validated['competency_ids'] ?? []
        );

        unset($validated['competency_ids']);

        $validated['created_by'] = $request->user()->id;

        $training = DB::transaction(function () use (
            $validated,
            $competencyIds
        ) {
            $training = Training::create($validated);

            $training->competencies()->sync($competencyIds);

            return $training;
        });

        return redirect()
            ->route('trainings.show', $training)
            ->with('success', 'Training berhasil ditambahkan.');
    }

    /**
     * Display training details.
     */
    public function show(Request $request, Training $training): View
    {
        $training->load('competencies');

        if ($request->user()->role === 'admin') {
            $training->load('participants.user');
        } else {
            $training->load([
                'participants' => fn ($query) => $query
                    ->where('user_id', $request->user()->id),
            ]);
        }

        $training->loadCount('participants');

        return view('trainings.show', compact('training'));
    }

    /**
     * Show edit form.
     */
    public function edit(Training $training): View
    {
        $training->load('competencies');

        $competencies = Competency::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'category']);

        return view(
            'trainings.edit',
            compact('training', 'competencies')
        );
    }

    /**
     * Update training and its competency mappings.
     */
    public function update(
        Request $request,
        Training $training
    ): RedirectResponse {
        $validated = $request->validate($this->rules());

        $competencyIds = array_map(
            'intval',
            $validated['competency_ids'] ?? []
        );

        unset($validated['competency_ids']);

        // Retain previous mappings to competencies that have since
        // been deactivated. They cannot be newly selected in the form.
        $inactiveCompetencyIds = $training->competencies()
            ->where('competencies.is_active', false)
            ->pluck('competencies.id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $competencyIds = array_values(array_unique(array_merge(
            $competencyIds,
            $inactiveCompetencyIds
        )));

        DB::transaction(function () use (
            $training,
            $validated,
            $competencyIds
        ) {
            $training->update($validated);

            $training->competencies()->sync($competencyIds);
        });

        return redirect()
            ->route('trainings.show', $training)
            ->with('success', 'Training berhasil diperbarui.');
    }

    /**
     * Delete training.
     */
    public function destroy(Training $training): RedirectResponse
    {
        $training->delete();

        return redirect()
            ->route('trainings.index')
            ->with('success', 'Training berhasil dihapus.');
    }

    /**
     * Validation rules for training and competency mapping.
     */
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'organizer' => ['nullable', 'string', 'max:150'],
            'instructor' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:255'],

            'start_date' => ['required', 'date'],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'quota' => [
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],

            'status' => [
                'required',
                Rule::in([
                    'planned',
                    'in_progress',
                    'completed',
                    'cancelled',
                ]),
            ],

            'description' => ['nullable', 'string', 'max:10000'],

            'competency_ids' => [
                'nullable',
                'array',
            ],

            'competency_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('competencies', 'id')
                    ->where(fn ($query) => $query->where('is_active', true)),
            ],
        ];
    }
}
