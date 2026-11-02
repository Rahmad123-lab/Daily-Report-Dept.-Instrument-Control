
@extends('layouts.app')

@section('title', 'Training Recommendations')
@section('header', 'Training Recommendations')

@section('content')

<div class="space-y-6">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center
                sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Training Recommendations
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Rekomendasi pelatihan berdasarkan kesenjangan kompetensi.
            </p>
        </div>

        <a
            href="{{ route('employee-competencies.index') }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-xl border border-slate-300 bg-white
                   px-4 py-3 text-sm font-semibold text-slate-700
                   transition hover:bg-slate-50"
        >
            View Competencies
        </a>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Competency Gaps
            </p>

            <p class="mt-3 text-3xl font-bold text-amber-600">
                {{ number_format($totalGapRecords) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Records below target level
            </p>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Total Gap Levels
            </p>

            <p class="mt-3 text-3xl font-bold text-red-600">
                {{ number_format($totalGapLevels) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Accumulated level difference
            </p>

        </div>

    </div>


    {{-- =====================================================
         RECOMMENDATION LIST
    ====================================================== --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200
                bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-bold text-slate-800">
                Recommended Training Programs
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Pelatihan yang telah dipetakan ke kompetensi dengan gap.
            </p>

        </div>


        @if($recommendations->isEmpty())

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center
                            rounded-2xl bg-emerald-50 text-emerald-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m5 12 4 4L19 6"/>
                    </svg>

                </div>

                <h3 class="mt-4 font-semibold text-slate-800">
                    No Competency Gaps Found
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Belum ada kompetensi yang membutuhkan pengembangan
                    atau penilaian kompetensi belum tersedia.
                </p>

            </div>

        @else

            <div class="divide-y divide-slate-100">

                @foreach($recommendations as $record)

                    @php
                        $gap = $record->target_level - $record->current_level;
                        $mappedTrainings = $record->competency?->trainings ?? collect();
                    @endphp

                    <div class="p-6">

                        {{-- Employee and Competency --}}
                        <div class="flex flex-col gap-4
                                    sm:flex-row sm:items-start
                                    sm:justify-between">

                            <div class="min-w-0">

                                @if($isAdmin)
                                    <p class="text-xs font-semibold uppercase
                                              tracking-wider text-slate-500">
                                        {{ $record->employee?->name ?? 'Unknown Employee' }}
                                        · NIK:
                                        {{ $record->employee?->nik ?? '-' }}
                                    </p>
                                @endif

                                <h3 class="mt-1 text-lg font-bold text-slate-800">
                                    {{ $record->competency?->name ?? 'Unknown Competency' }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $record->competency?->code ?? '-' }}
                                    <span class="mx-1">·</span>
                                    {{ $record->competency?->category ?? '-' }}
                                </p>

                            </div>

                            <span class="inline-flex w-fit shrink-0 rounded-full
                                         bg-amber-50 px-3 py-1.5 text-xs
                                         font-bold text-amber-700">
                                Gap {{ $gap }} Levels
                            </span>

                        </div>


                        {{-- Level Comparison --}}
                        <div class="mt-5 grid grid-cols-2 gap-3 sm:max-w-md">

                            <div class="rounded-xl bg-slate-50 p-4">

                                <p class="text-xs text-slate-500">
                                    Current Level
                                </p>

                                <p class="mt-2 text-2xl font-bold text-slate-800">
                                    {{ $record->current_level }}
                                </p>

                            </div>

                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs text-blue-600">
                                    Target Level
                                </p>

                                <p class="mt-2 text-2xl font-bold text-blue-700">
                                    {{ $record->target_level }}
                                </p>

                            </div>

                        </div>


                        {{-- Training Recommendations --}}
                        <div class="mt-6">

                            <div class="flex flex-wrap items-center
                                        justify-between gap-2">

                                <h4 class="text-sm font-bold text-slate-800">
                                    Recommended Programs
                                </h4>

                                <span class="text-xs text-slate-500">
                                    {{ $mappedTrainings->count() }} available programs
                                </span>

                            </div>


                            @if($mappedTrainings->isEmpty())

                                <div class="mt-3 rounded-xl border
                                            border-dashed border-slate-300
                                            bg-slate-50 p-5">

                                    <p class="text-sm font-semibold text-slate-700">
                                        No mapped training available
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Belum ada program pelatihan aktif yang
                                        dipetakan ke kompetensi ini. Hubungi
                                        administrator untuk mengatur pemetaan.
                                    </p>

                                </div>

                            @else

                                <div class="mt-3 grid grid-cols-1 gap-3
                                            lg:grid-cols-2">

                                    @foreach($mappedTrainings as $training)

                                        <div class="rounded-xl border
                                                    border-slate-200 p-4
                                                    transition hover:border-blue-300
                                                    hover:shadow-sm">

                                            <div class="flex items-start
                                                        justify-between gap-3">

                                                <div class="min-w-0">

                                                    <h5 class="font-semibold
                                                               text-slate-800">
                                                        {{ $training->title }}
                                                    </h5>

                                                    <p class="mt-1 text-xs
                                                              text-slate-500">
                                                        {{ $training->organizer
                                                            ?: 'Organizer not specified' }}
                                                    </p>

                                                </div>

                                                <span class="shrink-0 rounded-full
                                                             px-2.5 py-1 text-xs
                                                             font-semibold
                                                    {{ $training->status === 'planned'
                                                        ? 'bg-blue-50 text-blue-700'
                                                        : 'bg-amber-50 text-amber-700' }}">
                                                    {{ $training->status === 'planned'
                                                        ? 'Planned'
                                                        : 'In Progress' }}
                                                </span>

                                            </div>

                                            <div class="mt-4 space-y-2">

                                                <p class="flex items-center gap-2
                                                          text-xs text-slate-500">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4 shrink-0"
                                                         fill="none" viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.8">
                                                        <rect x="3" y="5" width="18"
                                                              height="16" rx="2"/>
                                                        <path stroke-linecap="round"
                                                              d="M16 3v4M8 3v4M3 11h18"/>
                                                    </svg>

                                                    {{ $training->start_date->format('d M Y') }}
                                                    –
                                                    {{ $training->end_date->format('d M Y') }}

                                                </p>

                                                <p class="flex items-center gap-2
                                                          text-xs text-slate-500">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4 shrink-0"
                                                         fill="none" viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.8">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                                        <circle cx="12" cy="10" r="2.5"/>
                                                    </svg>

                                                    {{ $training->location ?: 'Location not specified' }}

                                                </p>

                                            </div>

                                            <div class="mt-4 border-t
                                                        border-slate-100 pt-3">

                                                <a
                                                    href="{{ route('trainings.show', $training) }}"
                                                    class="inline-flex items-center gap-2
                                                           text-sm font-semibold text-blue-600
                                                           transition hover:text-blue-800"
                                                >
                                                    View Training Details

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.8">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M5 12h14m-7-7 7 7-7 7"/>
                                                    </svg>

                                                </a>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @endif

                        </div>


                        <div class="mt-4 text-xs text-slate-400">
                            Last assessed:
                            {{ $record->assessed_at?->format('d M Y') ?? '-' }}
                        </div>

                    </div>

                @endforeach

            </div>


            <div class="border-t border-slate-200 px-5 py-4">
                {{ $recommendations->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
