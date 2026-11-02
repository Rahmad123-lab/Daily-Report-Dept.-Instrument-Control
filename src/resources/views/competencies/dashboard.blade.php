@extends('layouts.app')

@section('title', 'Competency Gap Analysis')
@section('header', 'Competency Gap Analysis')

@section('content')

<div class="space-y-6">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center
                sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Competency Gap Dashboard
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Analisis tingkat kompetensi aktual terhadap target
                pengembangan karyawan.
            </p>
        </div>

        <a
            href="{{ route('employee-competencies.index') }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-xl bg-blue-600 px-5 py-3
                   text-sm font-semibold text-white
                   shadow-sm transition hover:bg-blue-700"
        >
            Manage Assessments
        </a>

    </div>


    {{-- =====================================================
         KPI CARDS
    ====================================================== --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        {{-- Total Assignments --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Total Assignments
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-800">
                {{ number_format($totalAssignments) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Competency records
            </p>
        </div>

        {{-- Assessed --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Assessed
            </p>

            <p class="mt-3 text-3xl font-bold text-blue-600">
                {{ number_format($assessedCount) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                {{ $assessmentRate }}% assessment coverage
            </p>
        </div>

        {{-- Not Assessed --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Not Assessed
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-600">
                {{ number_format($notAssessedCount) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Waiting for assessment
            </p>
        </div>

        {{-- Target Met --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Target Met
            </p>

            <p class="mt-3 text-3xl font-bold text-emerald-600">
                {{ number_format($targetMetCount) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                {{ $targetMetRate }}% of assessed records
            </p>
        </div>

        {{-- Below Target --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Below Target
            </p>

            <p class="mt-3 text-3xl font-bold text-amber-600">
                {{ number_format($gapCount) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Records requiring development
            </p>
        </div>

        {{-- Total Gap --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Total Gap Levels
            </p>

            <p class="mt-3 text-3xl font-bold text-red-600">
                {{ number_format($totalGapLevels) }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Accumulated competency level difference
            </p>
        </div>

    </div>


    {{-- =====================================================
         ASSESSMENT COVERAGE
    ====================================================== --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>
                <h2 class="font-bold text-slate-800">
                    Assessment Coverage
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Progress penilaian dari seluruh kompetensi
                    yang sudah ditetapkan.
                </p>
            </div>

            <span class="text-xl font-bold text-blue-600">
                {{ $assessmentRate }}%
            </span>

        </div>

        <div class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100">

            <div
                class="h-full rounded-full bg-blue-600 transition-all duration-500"
                style="width: {{ $assessmentRate }}%"
            ></div>

        </div>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
            <p class="text-xs text-slate-500">
                {{ $assessedCount }} assessed
                / {{ $totalAssignments }} assigned
            </p>

            <p class="text-xs font-medium text-slate-500">
                {{ $notAssessedCount }} pending
            </p>
        </div>

    </div>


    {{-- =====================================================
         GAP BY CATEGORY
    ====================================================== --}}

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-bold text-slate-800">
                Competency Gap by Category
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Kategori dengan akumulasi selisih kompetensi terbesar.
            </p>

        </div>

        @if($gapByCategory->isEmpty())

            <div class="px-6 py-12 text-center">
                <p class="font-semibold text-slate-800">
                    No Competency Gap Found
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Belum ada penilaian yang berada di bawah target.
                </p>
            </div>

        @else

            <div class="space-y-5 p-6">

                @foreach($gapByCategory as $category)
                    @php
                        $percentage = $totalGapLevels > 0
                            ? ($category->total_gap / $totalGapLevels) * 100
                            : 0;
                    @endphp

                    <div>

                        <div class="flex flex-col gap-2 sm:flex-row
                                    sm:items-center sm:justify-between">

                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $category->category }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $category->affected_records }}
                                    affected assessment records
                                </p>
                            </div>

                            <p class="text-sm font-bold text-amber-700">
                                {{ $category->total_gap }} gap levels
                            </p>

                        </div>

                        <div class="mt-3 h-2.5 overflow-hidden rounded-full
                                    bg-slate-100">

                            <div
                                class="h-full rounded-full bg-amber-500"
                                style="width: {{ $percentage }}%"
                            ></div>

                        </div>

                    </div>
                @endforeach

            </div>

        @endif

    </div>


    {{-- =====================================================
         PRIORITY GAP TABLE
    ====================================================== --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200
                bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-bold text-slate-800">
                Development Priority
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Daftar assessment dengan selisih kompetensi terbesar.
            </p>

        </div>

        @if($priorityRecords->isEmpty())

            <div class="px-6 py-12 text-center">
                <p class="font-semibold text-slate-800">
                    No Development Priority
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Tidak ditemukan kompetensi yang berada di bawah target.
                </p>
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>
                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Employee
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Competency
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Current
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Target
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Gap
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Assessed Date
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($priorityRecords as $record)

                            @php
                                $gap = $record->target_level
                                    - $record->current_level;
                            @endphp

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $record->employee?->name ?? 'Unknown' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $record->employee?->nik ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $record->competency?->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $record->competency?->code ?? '-' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="text-sm font-semibold text-slate-800">
                                        Level {{ $record->current_level }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="text-sm font-semibold text-slate-800">
                                        Level {{ $record->target_level }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex rounded-full
                                                 bg-amber-50 px-3 py-1
                                                 text-xs font-bold text-amber-700">
                                        +{{ $gap }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                    {{ $record->assessed_at?->format('d M Y') ?? '-' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $priorityRecords->links() }}
            </div>

        @endif

    </div>

</div>

@endsection