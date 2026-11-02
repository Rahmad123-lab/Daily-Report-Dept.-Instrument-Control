@php
    $currentStatus = old('status', $training->status ?? 'planned');

    $startDate = old(
        'start_date',
        isset($training) ? $training->start_date?->format('Y-m-d') : ''
    );

    $endDate = old(
        'end_date',
        isset($training) ? $training->end_date?->format('Y-m-d') : ''
    );
@endphp

<div class="space-y-6">

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- Training Title --}}
        <div class="md:col-span-2">
            <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">
                Training Title <span class="text-red-500">*</span>
            </label>
            <input
                id="title"
                name="title"
                type="text"
                required
                maxlength="150"
                value="{{ old('title', $training->title ?? '') }}"
                placeholder="Contoh: PLC Troubleshooting Training"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm text-slate-800 placeholder:text-slate-400
                       focus:border-blue-500 focus:outline-none focus:ring-2
                       focus:ring-blue-100 @error('title') border-red-500 @enderror"
            >
            @error('title')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div>
            <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">
                Category
            </label>
            <input
                id="category"
                name="category"
                type="text"
                maxlength="100"
                value="{{ old('category', $training->category ?? '') }}"
                placeholder="Instrument, Safety, Automation..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
            @error('category')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Organizer --}}
        <div>
            <label for="organizer" class="mb-2 block text-sm font-semibold text-slate-700">
                Organizer
            </label>
            <input
                id="organizer"
                name="organizer"
                type="text"
                maxlength="150"
                value="{{ old('organizer', $training->organizer ?? '') }}"
                placeholder="Nama departemen atau penyelenggara"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
            @error('organizer')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Instructor --}}
        <div>
            <label for="instructor" class="mb-2 block text-sm font-semibold text-slate-700">
                Instructor
            </label>
            <input
                id="instructor"
                name="instructor"
                type="text"
                maxlength="150"
                value="{{ old('instructor', $training->instructor ?? '') }}"
                placeholder="Nama instruktur"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
            @error('instructor')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="mb-2 block text-sm font-semibold text-slate-700">
                Location
            </label>
            <input
                id="location"
                name="location"
                type="text"
                value="{{ old('location', $training->location ?? '') }}"
                placeholder="Lokasi atau platform pelatihan"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
            @error('location')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Start Date --}}
        <div>
            <label for="start_date" class="mb-2 block text-sm font-semibold text-slate-700">
                Start Date <span class="text-red-500">*</span>
            </label>
            <input
                id="start_date"
                name="start_date"
                type="date"
                required
                value="{{ $startDate }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
            @error('start_date')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- End Date --}}
        <div>
            <label for="end_date" class="mb-2 block text-sm font-semibold text-slate-700">
                End Date <span class="text-red-500">*</span>
            </label>
            <input
                id="end_date"
                name="end_date"
                type="date"
                required
                min="{{ $startDate }}"
                value="{{ $endDate }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
            @error('end_date')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Quota --}}
        <div>
            <label for="quota" class="mb-2 block text-sm font-semibold text-slate-700">
                Participant Quota
            </label>
            <input
                id="quota"
                name="quota"
                type="number"
                min="1"
                max="65535"
                value="{{ old('quota', $training->quota ?? '') }}"
                placeholder="Kosongkan jika tidak dibatasi"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
            @error('quota')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div>
            <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">
                Status <span class="text-red-500">*</span>
            </label>
            <select
                id="status"
                name="status"
                required
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >
                <option value="planned" @selected($currentStatus === 'planned')>
                    Planned
                </option>
                <option value="in_progress" @selected($currentStatus === 'in_progress')>
                    In Progress
                </option>
                <option value="completed" @selected($currentStatus === 'completed')>
                    Completed
                </option>
                <option value="cancelled" @selected($currentStatus === 'cancelled')>
                    Cancelled
                </option>
            </select>
            @error('status')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="md:col-span-2">
            <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">
                Description
            </label>
            <textarea
                id="description"
                name="description"
                rows="5"
                maxlength="10000"
                placeholder="Tujuan, materi, atau keterangan tambahan pelatihan..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm placeholder:text-slate-400
                       focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >{{ old('description', $training->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
        <a
            href="{{ isset($training) ? route('trainings.show', $training) : route('trainings.index') }}"
            class="inline-flex items-center justify-center rounded-xl
                   border border-slate-300 px-5 py-3 text-sm font-semibold
                   text-slate-700 transition hover:bg-slate-50"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   bg-blue-600 px-5 py-3 text-sm font-semibold text-white
                   shadow-sm transition hover:bg-blue-700
                   focus:outline-none focus:ring-2 focus:ring-blue-500
                   focus:ring-offset-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>
            </svg>
            {{ isset($training) ? 'Save Changes' : 'Create Training' }}
        </button>
    </div>

    
{{-- =========================================================
     LINKED COMPETENCIES
========================================================== --}}

@php
    $selectedCompetencyIds = array_map(
        'intval',
        (array) old(
            'competency_ids',
            isset($training)
                ? $training->competencies
                    ->where('is_active', true)
                    ->pluck('id')
                    ->all()
                : []
        )
    );

    $inactiveMappedCompetencies = isset($training)
        ? $training->competencies->where('is_active', false)
        : collect();
@endphp

<div class="md:col-span-2">
    <div class="mb-3">
        <label class="block text-sm font-semibold text-slate-700">
            Related Competencies
        </label>

        <p class="mt-1 text-xs text-slate-500">
            Pilih kompetensi yang akan dikembangkan melalui program pelatihan ini.
            Kamu dapat memilih lebih dari satu.
        </p>
    </div>

    @if($competencies->isEmpty())
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
            <p class="text-sm font-medium text-amber-800">
                Belum ada kompetensi aktif.
            </p>
            <p class="mt-1 text-xs text-amber-700">
                Tambahkan master kompetensi terlebih dahulu sebelum membuat pemetaan.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @foreach($competencies as $competency)
                <label
                    for="competency_{{ $competency->id }}"
                    class="flex cursor-pointer items-start gap-3 rounded-xl
                           border border-slate-200 p-4 transition
                           hover:border-blue-300 hover:bg-blue-50/40"
                >
                    <input
                        id="competency_{{ $competency->id }}"
                        type="checkbox"
                        name="competency_ids[]"
                        value="{{ $competency->id }}"
                        @checked(in_array(
                            (int) $competency->id,
                            $selectedCompetencyIds,
                            true
                        ))
                        class="mt-1 h-4 w-4 rounded border-slate-300
                               text-blue-600 focus:ring-blue-500"
                    >

                    <span class="min-w-0">
                        <span class="block text-sm font-semibold text-slate-800">
                            {{ $competency->name }}
                        </span>

                        <span class="mt-1 block text-xs text-slate-500">
                            {{ $competency->code }}
                        </span>

                        <span class="mt-1 block text-xs text-slate-400">
                            {{ $competency->category }}
                        </span>
                    </span>
                </label>
            @endforeach
        </div>
    @endif

    @error('competency_ids')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @error('competency_ids.*')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if($inactiveMappedCompetencies->isNotEmpty())
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs font-semibold text-slate-700">
                Previously linked inactive competencies
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Pemetaan lama dipertahankan dan tidak dapat dipilih sebagai
                pemetaan baru selama kompetensi nonaktif.
            </p>

            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($inactiveMappedCompetencies as $inactiveCompetency)
                    <span class="rounded-lg bg-slate-200 px-3 py-1.5
                                 text-xs text-slate-600">
                        {{ $inactiveCompetency->code }} —
                        {{ $inactiveCompetency->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif
</div>

</div>